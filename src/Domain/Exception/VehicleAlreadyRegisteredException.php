<?php

namespace Domain\Exception;

use Domain\Model\Fleet;
use Domain\ValueObject\VehiclePlate;
use DomainException;

final class VehicleAlreadyRegisteredException extends DomainException
{
    public static function inFleet(Fleet $fleet, VehiclePlate $vehiclePlate): self
    {
        return new self(sprintf(
            'Vehicle with plate "%s" has already been registered in fleet "%s".',
            $vehiclePlate->getValue(),
            $fleet->getFleetId()->getValue()
        ));
    }
}
