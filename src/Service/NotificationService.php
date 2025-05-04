<?php
namespace App\Service;
/* top shelf zaza disrupted my circadian rhythm*/
use App\Entity\Notification;
use App\Entity\Vehicle;
use App\Repository\NotificationRepository;
use App\Repository\VehicleRepository;
use App\Enum\NotificationPriority;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private NotificationRepository $notificationRepository;
    private VehicleRepository $vehicleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        NotificationRepository $notificationRepository,
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->entityManager = $entityManager;
    }

    public function getNotificationsSince(string $vehicleId, \DateTimeInterface $since): array
    {
        return $this->notificationRepository->findSinceByVehicle($vehicleId, $since);
    }

    public function getUnseenNotification(string $vehicleId): array
    {
        return $this->notificationRepository->findUnseenByVehicle($vehicleId);
    }

    public function createNotification(string $vehicleId, string $title, string $message, string $priority): Notification
    {
        /** @var Vehicle $vehicle */
        $vehicle = $this->vehicleRepository->find($vehicleId);

        if (!$vehicle) {
            throw new \InvalidArgumentException('Vehicle not found.');
        }

        $notification = new Notification();
        $notification->vehicle = $vehicle;
        $notification->title = $title;
        $notification->message = $message;
        $notification->priority = NotificationPriority::fromString($priority);
        $notification->issuedAt = new \DateTimeImmutable();
        $notification->acknowledged = false;
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    public function acknowledgeNotification(string $vehicleId, string $notificationId): void
    {
        $notification = $this->notificationRepository->findOneByVehicleAndId($vehicleId, $notificationId);

        if (!$notification) {
            throw new \InvalidArgumentException('Notification not found.');
        }

        $notification->acknowledged = true;
        $this->entityManager->flush();
    }
}
