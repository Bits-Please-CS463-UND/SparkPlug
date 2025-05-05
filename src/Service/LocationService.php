<?php
// src/Service/LocationService.php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Geofence;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use App\Repository\LocationRepository;
use App\Repository\GeofenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class LocationService
{
    private VehicleRepository $vehicleRepository;
    private LocationRepository $locationRepository;
    private GeofenceRepository $geofenceRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        VehicleRepository $vehicleRepository,
        LocationRepository $locationRepository,
        GeofenceRepository $geofenceRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->locationRepository = $locationRepository;
        $this->geofenceRepository = $geofenceRepository;
        $this->entityManager = $entityManager;
    }

    public function getCurrentLocation(string $vehicleId): ?Location
    {
        return $this->locationRepository->findLatestByVehicleId($vehicleId);
    }

    public function setCurrentLocation(string $vehicleId, float $lat, float $lng): Location
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        if (!$vehicle instanceof Vehicle) {
            throw new \InvalidArgumentException('Vehicle not found.');
        }

        $location = new Location();
        $location->setLat($lat);
        $location->setLng($lng);

        $vehicle->locations->add($location);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $location;
    }

    public function getLocationLog(string $vehicleId): array
    {
        return $this->locationRepository->findAllByVehicleId($vehicleId);
    }

    public function getGeofence(string $vehicleId): ?Geofence
    {
        return $this->geofenceRepository->findByVehicleId($vehicleId);
    }

    public function setGeofence(string $vehicleId, float $lat, float $lng, float $radius): Geofence
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        if (!$vehicle) {
            throw new \InvalidArgumentException('Vehicle not found.');
        }

        $geofence = $this->geofenceRepository->findByVehicleId($vehicleId);
        if (!$geofence) {
            $geofence = new Geofence();
        }

        $geofence->setLat($lat);
        $geofence->setLng($lng);
        $geofence->setRadius($radius);
        $geofence->setVehicle($vehicle);

        $this->entityManager->persist($geofence);
        $this->entityManager->flush();

        return $geofence;
    }
}
