<?php
// src/Repository/GeofenceRepository.php

namespace App\Repository;

use App\Entity\Geofence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GeofenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geofence::class);
    }

    public function findByVehicleId(string $vehicleId): ?Geofence
    {
        return $this->createQueryBuilder('g')
            ->join('g.vehicle', 'v')
            ->where('v.id = :vehicleId')
            ->setParameter('vehicleId', $vehicleId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
