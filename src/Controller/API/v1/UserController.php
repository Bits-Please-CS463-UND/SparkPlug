<?php
declare(strict_types=1);

/**#[Route(
    path: "/api/v1/user",
    name: "api.v1.user"
)] */
//josephs old #route code, please replace my route if i did something wrong im just going off of the "Cade please implement" section lmao

namespace App\Controller\API\v1;

use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/vehicle/{vehicleId}/drivers')]
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private VehicleRepository $vehicleRepository;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleRepository $vehicleRepository,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleRepository = $vehicleRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('', name: 'grant_vehicle_access', methods: ['POST'])]
    public function grantAccess(string $vehicleId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['error' => 'Email is required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $vehicle = $this->vehicleRepository->find($vehicleId);
        if (!$vehicle) {
            return new JsonResponse(['error' => 'Vehicle not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $vehicle->addDriver($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{driverId}', name: 'remove_vehicle_access', methods: ['DELETE'])]
    public function removeAccess(string $vehicleId, string $driverId): JsonResponse
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        if (!$vehicle) {
            return new JsonResponse(['error' => 'Vehicle not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository->find($driverId);
        if (!$user) {
            return new JsonResponse(['error' => 'Driver not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $vehicle->removeDriver($user);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
