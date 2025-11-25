<?php

namespace App\Domain\Repository;

use App\Domain\Model\Fleet;
use App\Domain\ValueObject\FleetId;

interface FleetRepositoryInterface
{
    public function save(Fleet $fleet): void;
    public function find(FleetId $id): ?Fleet;
}
