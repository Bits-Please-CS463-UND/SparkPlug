<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Geofence
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: Vehicle::class, inversedBy: 'geofence')]
    #[ORM\JoinColumn(nullable: false)]
    private Vehicle $vehicle;

    #[ORM\Column(type: 'float')]
    private float $lat;

    #[ORM\Column(type: 'float')]
    private float $lng;

    #[ORM\Column(type: 'float')]
    private float $radius;

    public function getId(): int
    {
        return $this->id;
    }

    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;
        return $this;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }

    public function setRadius(float $radius): self
    {
        $this->radius = $radius;
        return $this;
    }
}
