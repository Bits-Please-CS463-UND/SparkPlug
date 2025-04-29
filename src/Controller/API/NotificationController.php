<?php
namespace App\Controller\API;
/* they must have amnesia they forgot that im him*/
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @Route("/api/v1/vehicle/{vehicleId}/notifications", methods={"GET"})
     */
    public function getNotifications(string $vehicleId): JsonResponse
    {
        $notifications = $this->notificationService->getUnseenNotification($vehicleId);

        return $this->json($notifications);
    }

    /**
     * @Route("/api/v1/vehicle/{vehicleId}/notifications", methods={"POST"})
     */
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

    /**
     * @Route("/api/v1/vehicle/{vehicleId}/notifications/{notificationId}", methods={"DELETE"})
     */
    public function acknowledgeNotification(string $vehicleId, string $notificationId): JsonResponse
    {
        $this->notificationService->acknowledgeNotification($vehicleId, $notificationId);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}