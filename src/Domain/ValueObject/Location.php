<?php

namespace Domain\ValueObject;

final class Location
{
    public function __construct(
        private float $latitude,
        private float $longitude,
        private ?float $altitude = null
    ) {}

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function equals(Location $location): bool
    {
        return $this->latitude === $location->getLatitude() &&
               $this->longitude === $location->getLongitude() &&
               $this->altitude === $location->getAltitude()
        ;
    }
}
