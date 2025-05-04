<?php
declare(strict_types=1);

namespace App\Struct;

use App\Entity\Geofence;
use App\Entity\Location;
use App\Entity\Vehicle;

class SerializedVehicle
{
    public readonly string $make;
    public readonly string $model;
    public readonly int $year;
    public readonly string $color;
    public readonly ?Location $currentLocation;
    /** @var array<Location> */
    public readonly array $locationHistory;
    public readonly ?Geofence $geofence;

    public function __construct(
        Vehicle $vehicle
    ){
        $this->make = $vehicle->make;
        $this->model = $vehicle->model;
        $this->year = $vehicle->year;
        $this->color = $vehicle->color;
        $this->currentLocation = $vehicle->locations->isEmpty() ? null : $vehicle->locations->get(0);
        $this->locationHistory = $vehicle->locations->toArray();
        $this->geofence = $vehicle->geofence;
    }
}