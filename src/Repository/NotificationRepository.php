<?php
/* majority of my code was written while staring at documentation in a haze so it could totally be bullshit the code was basically writing itself too this stuff is crazy*/
namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @param string $vehicleId
     * @return Notification[]
     */
    public function findUnseenByVehicle(string $vehicleId): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.vehicle = :vehicleId')
            ->andWhere('n.acknowledged = false')
            ->setParameter('vehicleId', $vehicleId)
            ->orderBy('n.issuedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $vehicleId
     * @param string $notificationId
     * @return Notification|null
     */
    public function findOneByVehicleAndId(string $vehicleId, string $notificationId): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.vehicle = :vehicleId')
            ->andWhere('n.id = :notificationId')
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('notificationId', $notificationId)
            ->getQuery()
            ->getOneOrNullResult();
    }
//ignore the horrendous function name im crashing out
    public function findSinceByVehicle(string $vehicleId, \DateTimeInterface $since): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.vehicle', 'v')
            ->where('v.id = :vehicleId')
            ->andWhere('n.issuedAt >= :since')
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('since', $since)
            ->orderBy('n.issuedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
