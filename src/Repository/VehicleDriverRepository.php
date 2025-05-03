<?php

namespace App\Repository;

use App\Entity\Driver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehicleDriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    public function findByVehicleAndUser(string $vehicleId, string $userId): ?Driver
    {
        return $this->findOneBy(['vehicle' => $vehicleId, 'user' => $userId]);
    }
}
