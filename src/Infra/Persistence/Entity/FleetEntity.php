<?php

namespace Infra\Persistence\Entity;

use Doctrine\ORM\Mapping as ORM;
use Infra\Persistence\FleetRepository;

#[ORM\Entity(repositoryClass: FleetRepository::class)]
#[ORM\Table(name: 'fleet')]
class FleetEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $fleetId = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $userId = null;

    /**
     * RegisteredVehicles : ['plate' => ['lat' => , 'lng' => , 'alt' => ]]
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $vehicles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFleetId(): ?string
    {
        return $this->fleetId;
    }

    public function setFleetId(string $fleetId): self
    {
        $this->fleetId = $fleetId;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getVehicles(): ?array
    {
        return $this->vehicles;
    }

    public function setVehicles(?array $vehicles): self
    {
        $this->vehicles = $vehicles;
        return $this;
    }
}
