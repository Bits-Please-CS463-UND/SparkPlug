<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

readonly class VehicleCreationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    public function create(
        User $owner,
        ?string $make,
        ?string $model,
        ?int $year,
        ?string $color,
        ?string $vin,
        ?string $licensePlates
    ): Vehicle {
        // Fill in vehicle details
        $vehicle = new Vehicle();
        $vehicle->make = $make;
        $vehicle->model = $model;
        $vehicle->year = $year;
        $vehicle->color = $color;
        $vehicle->vin = $vin;
        $vehicle->licensePlates = $licensePlates;
        $vehicle->owner = $owner;

        // Add association to owner
        $vehicle->drivers->add($owner);
        $owner->ownedVehicles->add($vehicle);
        $owner->sharedVehicles->add($vehicle);

        // Write out
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return $vehicle;
    }
}