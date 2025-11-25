<?php

use App\App\Command\ParkVehicleCommand;
use App\App\Command\RegisterVehicleCommand;
use App\App\Exception\VehicleAlreadyRegisteredException;
use App\App\Exception\VehicleAlreadyParkedException;
use App\App\Handler\ParkVehicleHandler;
use App\App\Handler\RegisterVehicleHandler;
use App\App\Query\GetVehicleLocationQuery;
use App\App\QueryHandler\GetVehicleLocationHandler;
use App\Domain\Model\Fleet;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\Location;
use App\Domain\ValueObject\VehicleId;
use App\Infra\Persistence\InMemoryFleetRepository;
use Behat\Behat\Context\Context;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use PHPUnit\Framework\Assert;
use Throwable;

final class DomainContext implements Context
{
    private InMemoryFleetRepository $repository;
    private RegisterVehicleHandler $registerHandler;
    private ParkVehicleHandler $parkHandler;
    private GetVehicleLocationHandler $queryHandler;

    private string $myFleetId = 'my-fleet-id';
    private string $anotherFleetId = 'another-fleet-id';
    private string $vehiclePlate = 'AZE-RTY-123';

    private ?Throwable $lastException = null;

    public function __construct()
    {
        $this->repository = new InMemoryFleetRepository();
        $this->registerHandler = new RegisterVehicleHandler($this->repository);
        $this->parkHandler = new ParkVehicleHandler($this->repository);
        $this->queryHandler = new GetVehicleLocationHandler($this->repository);
    }

    #[BeforeScenario]
    public function cleanState(): void
    {
        $this->repository->reset(); 
        $this->lastException = null;
    }

    #[Given('my fleet')]
    public function myFleet(): void
    {
        $myFleet = new Fleet(new FleetId($this->myFleetId));
        $this->repository->save($myFleet);
    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {
        $anotherUserFleet = new Fleet(new FleetId($this->anotherFleetId));
        $this->repository->save($anotherUserFleet);
    }

    #[Given('a vehicle')]
    public function aVehicle(): string
    {
        return $this->vehiclePlate;
    }

    #[Given('I have registered this vehicle into my fleet')]
    public function iHaveRegisteredThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicleCommand($this->myFleetId, $this->vehiclePlate);
        ($this->registerHandler)($command);
    }

    #[Given("this vehicle has been registered into the other user's fleet")]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $command = new RegisterVehicleCommand($this->anotherFleetId, $this->vehiclePlate);
        ($this->registerHandler)($command);
    }

    #[Given('a location')]
    public function aLocation(): Location
    {
        return $this->getLocation(); 
    }

    #[Given('my vehicle has been parked into this location')]
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        $location = $this->getLocation();
        $command = new ParkVehicleCommand($this->myFleetId, $this->vehiclePlate, $location->getLatitude(), $location->getLongitude(), $location->getAltitude());
        ($this->parkHandler)($command);
    }

    #[When('I try to register this vehicle into my fleet')]
    #[When('I register this vehicle into my fleet')]
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicleCommand($this->myFleetId, $this->vehiclePlate);

        try {
            ($this->registerHandler)($command);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    #[When('I park my vehicle at this location')]
    #[When('I try to park my vehicle at this location')]
    public function iParkMyVehicleAtThisLocation(): void
    {
        $location = $this->getLocation();
        $command = new ParkVehicleCommand($this->myFleetId, $this->vehiclePlate, $location->getLatitude(), $location->getLongitude(), $location->getAltitude());

        try {
            ($this->parkHandler)($command);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        Assert::assertNull($this->lastException, 'Registration failed with an unexpected exception.');

        $fleet = $this->repository->find(new FleetId($this->myFleetId));

        Assert::assertTrue(
            $fleet->isVehicleRegistered(new VehicleId($this->vehiclePlate)), 
            sprintf('Vehicle "%s" was not found in the fleet "%s".', $this->vehiclePlate, $this->myFleetId)
        );
    }

    #[Then('I should be informed this vehicle has already been registered into my fleet')]
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        Assert::assertInstanceOf(VehicleAlreadyRegisteredException::class, $this->lastException, 'Expected a VehicleAlreadyRegisteredException.');
    }

    #[Then('the known location of my vehicle should verify this location')]
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $query = new GetVehicleLocationQuery($this->myFleetId, $this->vehiclePlate);
        $location = ($this->queryHandler)($query);

        Assert::assertNull($this->lastException, 'Parking failed with an unexpected exception.');
        Assert::assertNotNull($location, 'Vehicle location was not found.');
        Assert::assertTrue($this->getLocation()->equals($location), 'Vehicle location does not match expected location.');
    }

    #[Then('I should be informed that my vehicle is already parked at this location')]
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        Assert::assertInstanceOf(VehicleAlreadyParkedException::class, $this->lastException, 'Expected a VehicleAlreadyParkedException.');
    }

    private function getLocation(): Location
    {
        return new Location(1.00, 1.00, 1.00); 
    }
}
