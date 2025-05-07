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
        * @param string $userId
        * @return Notification[]
         */
    public function findUnseenByUser(string $userId): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :userId')
            ->andWhere('n.acknowledged = false')
            ->setParameter('userId', $userId)
            ->orderBy('n.issuedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $userId
     * @param string $notificationId
     * @return Notification|null
     */
    public function findOneByUserAndId(string $userId, string $notificationId): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :userId')
            ->andWhere('n.id = :notificationId')
            ->setParameter('userId', $userId)
            ->setParameter('notificationId', $notificationId)
            ->getQuery()
            ->getOneOrNullResult();
    }
//ignore the horrendous function name im crashing out
    public function findSinceByUser(string $userId, \DateTimeInterface $since): array
    {
        return $this->createQueryBuilder('n')
            ->join('n.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('n.issuedAt >= :since')
            ->setParameter('userId', $userId)
            ->setParameter('since', $since)
            ->orderBy('n.issuedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

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

}
