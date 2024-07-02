<?php

namespace App\BusinessLogic\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Coords implements \JsonSerializable
{
    #[Assert\Range(min: -180, max: 180)]
    private float $latitude = 0.0;

    #[Assert\Range(min: -180, max: 180)]
    private float $longitude = 0.0;

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return ['lat' => $this->latitude, 'lng' => $this->longitude];
    }
}
