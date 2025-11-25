<?php

namespace App\App\Exception;

use App\Domain\ValueObject\FleetId;
use Exception;

final class FleetNotFoundException extends Exception
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
