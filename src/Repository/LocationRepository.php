<?php


namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findLatestByVehicleId(string $vehicleId): ?Location
    {
        return $this->createQueryBuilder('l')
            ->join('l.vehicle', 'v')
            ->where('v.id = :vehicleId')
            ->setParameter('vehicleId', $vehicleId)
            ->orderBy('l.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByVehicleId(string $vehicleId): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.vehicle', 'v')
            ->where('v.id = :vehicleId')
            ->setParameter('vehicleId', $vehicleId)
            ->orderBy('l.timestamp', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
