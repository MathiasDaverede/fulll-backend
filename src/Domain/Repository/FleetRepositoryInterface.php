<?php

namespace Domain\Repository;

use Domain\Model\Fleet;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\UserId;

interface FleetRepositoryInterface
{
    public function findOneByFleetId(FleetId $id): ?Fleet;
    public function create(UserId $userId): Fleet;
    public function save(Fleet $fleet): void;
}
