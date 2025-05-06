<?php
declare(strict_types=1);

namespace App\Struct;

use App\Entity\Geofence;
use App\Entity\Location;
use App\Entity\Vehicle;

class SerializedVehicle
{
    public readonly string $id;
    public readonly string $make;
    public readonly string $model;
    public readonly int $year;
    public readonly string $color;
    public readonly ?array $currentLocation;
    /** @var array<Location> */
    public readonly array $locationHistory;
    public readonly ?array $geofence;

    public function __construct(
        Vehicle $vehicle
    ){
        $this->id = $vehicle->id;
        $this->make = $vehicle->make;
        $this->model = $vehicle->model;
        $this->year = $vehicle->year;
        $this->color = $vehicle->color;
        $this->locationHistory = array_map(
            function (Location $l) {
                return [
                    'lat' => $l->latitude,
                    'lng' => $l->longitude,
                ];
            },
            $vehicle->locations->toArray()
        );
        $this->currentLocation = empty($this->locationHistory) ? null : $this->locationHistory[0];
        $this->geofence = $vehicle->geofence ? [
            'radius' => $vehicle->geofence->getRadius(),
            'center' => [
                'lat' => $vehicle->geofence->getLat(),
                'lng' => $vehicle->geofence->getLng(),
            ]
        ] : null;
    }
}