<?php
namespace App\Controller\API\v1;
/* they must have amnesia they forgot that im him*/

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Response\NotificationBundle;
use App\Service\NotificationService;
use App\Struct\SerializedNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/notifications', name: 'api.v1.notifications.')]
class NotificationController extends AbstractController
{

    public function __construct(
        private NotificationService $notificationService
    ){}

    #[Route(path: '/{vehicleId}', name: 'list', methods: ['GET'])]
    public function getNotifications(string $vehicleId): JsonResponse
    {
        $notifications = $this->notificationService->getUnseenNotification($vehicleId);

        return $this->json($notifications);
    }

    #[Route(path: '/all/{user}', name: '.all', methods: ['GET'])]
    public function getAll(User $user): JsonResponse
    {
        $notifications = [];
        foreach($user->sharedVehicles as $vehicle){
            /** @var Vehicle $vehicle */
            $notifications += array_map(
                function (Notification $notification){
                    return new SerializedNotification($notification);
                },
                $vehicle->notifications->filter(function (Notification $n) { return !$n->acknowledged; })->toArray()
            );
        }

        return new JsonResponse(
            new NotificationBundle(
                "hai",
                'wsg bruh',
                $notifications,
            )
        );
    }
// update notifs system
    #[Route(path: '/deterministic', name: 'deterministic', methods: ['GET'])]
    public function getNotificationsSince(string $vehicleId, Request $request): JsonResponse
    {
        $since = $request->query->get('since');
        $notifications = [];

        if ($since) {
            try {
                $sinceDateTime = new \DateTime($since);
                $notifications = $this->notificationService->getNotificationsSince($vehicleId, $sinceDateTime);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Invalid date'], 400);
            }

            if (empty($notifications)) {
                return new JsonResponse(null, JsonResponse::HTTP_NOT_MODIFIED);
            }
        } else {
            $notifications = $this->notificationService->getUnseenNotification($vehicleId);
        }

        return $this->json($notifications);
    }

    #[Route(path: '', name: 'create', methods: ['POST'])]
    public function sendNotification(string $vehicleId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $notification = $this->notificationService->createNotification(
            $vehicleId,
            $data['title'],
            $data['message'],
            $data['priority']
        );

        return new JsonResponse([
            'id' => $notification->id,
            'issuedAt' => $notification->issuedAt->format(DATE_ATOM)
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/{notificationId}', name: 'acknowledge', methods: ['DELETE'])]
    public function acknowledgeNotification(string $vehicleId, string $notificationId): JsonResponse
    {
        $this->notificationService->acknowledgeNotification($vehicleId, $notificationId);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}