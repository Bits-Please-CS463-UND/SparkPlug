<?php
namespace App\Service;

use App\Entity\Driver;
use App\Entity\Vehicle;
use App\Entity\User;
use App\Repository\VehicleDriverRepository;
use Doctrine\ORM\EntityManagerInterface;

class DriverService
{
    private EntityManagerInterface $entityManager;
    private VehicleDriverRepository $vehicleDriverRepository;

    public function __construct(EntityManagerInterface $entityManager, VehicleDriverRepository $vehicleDriverRepository)
    {
        $this->entityManager = $entityManager;
        $this->vehicleDriverRepository = $vehicleDriverRepository;
    }

    public function grantAccessToVehicle(string $vehicleId, string $email): Driver
    {
        $vehicle = $this->entityManager->getRepository(Vehicle::class)->find($vehicleId);
        if (!$vehicle) {
            throw new \Exception('Vehicle not found.');
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw new \Exception('User not found.');
        }

        $driver = new Driver();
        $driver->setVehicle($vehicle);
        $driver->setUser($user);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }

    public function removeAccessFromVehicle(string $vehicleId, string $driverId): void
    {
        $driver = $this->vehicleDriverRepository->findByVehicleAndUser($vehicleId, $driverId);
        if (!$driver) {
            throw new \Exception('Driver not found.');
        }

        $this->entityManager->remove($driver);
        $this->entityManager->flush();
    }
}
