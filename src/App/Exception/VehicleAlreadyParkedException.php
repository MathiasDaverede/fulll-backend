<?php

namespace App\App\Exception;

use App\Domain\ValueObject\VehicleId;
use Exception;

final class VehicleAlreadyParkedException extends Exception
{
    public function __construct(private VehicleId $vehicleId)
    {
        $message = sprintf(
            'Vehicle "%s" is already parked at this location.',
            $vehicleId->getValue()
        );

        parent::__construct($message);
    }
}
