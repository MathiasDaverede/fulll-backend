<?php

namespace Domain\Exception;

use Domain\ValueObject\FleetId;
use DomainException;

final class FleetNotFoundException  extends DomainException
{
    public function __construct(private FleetId $fleetId)
    {
        $message = sprintf(
            'Fleet with ID "%s" not found.',
            $this->fleetId->getValue()
        );

        parent::__construct($message);
    }
}
