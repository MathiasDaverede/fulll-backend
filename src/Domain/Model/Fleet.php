<?php

namespace Domain\Model;

use Domain\Exception\VehicleAlreadyParkedException;
use Domain\Exception\VehicleAlreadyRegisteredException;
use Domain\Exception\VehicleNotRegisteredException;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;
use Domain\ValueObject\UserId;
use Domain\ValueObject\VehiclePlate;

class Fleet
{
    /**
     * @var array<string, ?Location> key: Plate, Value: Location (null if not parked)
     */
    private array $vehicles = [];

    public function __construct(
        private UserId $userId,
        private FleetId $fleetId,
    ) {}

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getFleetId(): FleetId
    {
        return $this->fleetId;
    }

    /**
     * @return array<string, ?Location>
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    public function getVehicleLocation(VehiclePlate $vehiclePlate): ?Location
    {
        return $this->vehicles[$vehiclePlate->getValue()];
    }

    public function isVehicleRegistered(VehiclePlate $vehiclePlate): bool
    {
        return array_key_exists($vehiclePlate->getValue(), $this->vehicles);
    }

    public function setVehicles(array $vehicles): self
    {
        $this->vehicles = $vehicles;

        return $this;
    }

    public function registerVehicle(VehiclePlate $vehiclePlate): void
    {
        if ($this->isVehicleRegistered($vehiclePlate)) {
            throw VehicleAlreadyRegisteredException::inFleet($this, $vehiclePlate);
        }

        $this->vehicles[$vehiclePlate->getValue()] = null;
    }

    public function parkVehicle(VehiclePlate $vehiclePlate, Location $location): void
    {
        if (!$this->isVehicleRegistered($vehiclePlate)) {
            throw VehicleNotRegisteredException::inFleet($this, $vehiclePlate);
        }

        $oldLocation = $this->vehicles[$vehiclePlate->getValue()];

        if (!is_null($oldLocation) && $oldLocation->equals($location)) {
            throw VehicleAlreadyParkedException::atLocation($vehiclePlate, $location);
        }

        $this->vehicles[$vehiclePlate->getValue()] = $location;
    }
}
