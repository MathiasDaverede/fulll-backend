<?php

namespace App\App\Exception;

use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\VehicleId;
use Exception;

final class VehicleNotRegisteredException extends Exception
{
    public function __construct(
        private FleetId $fleetId,
        private VehicleId $vehicleId
    ) {
        $message = sprintf(
            'Vehicle "%s" is not registered in fleet "%s".',
            $vehicleId->getValue(),
            $fleetId->getValue()
        );

        parent::__construct($message);
    }
}
