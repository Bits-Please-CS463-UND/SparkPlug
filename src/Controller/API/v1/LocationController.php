<?php
// src/Controller/API/LocationController.php

namespace App\Controller\API\v1;

use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/vehicle/{vehicleId}/location', name: 'api.v1.vehicle.location.')]
class LocationController extends AbstractController
{
    private LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    #[Route(path: '/current', name: 'get_current', methods: ['GET'])]
    public function getCurrentLocation(string $vehicleId): JsonResponse
    {
        $location = $this->locationService->getCurrentLocation($vehicleId);
        if (!$location) {
            return new JsonResponse(['error' => 'Location not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $location->getId(),
            'lat' => $location->getLat(),
            'lng' => $location->getLng(),
        ]);
    }

    #[Route(path: '/current', name: 'set_current', methods: ['POST'])]
    public function setCurrentLocation(string $vehicleId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['lat'], $data['lng'])) {
            return new JsonResponse(['error' => 'Missing latitude or longitude.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $location = $this->locationService->setCurrentLocation($vehicleId, $data['lat'], $data['lng']);

        return new JsonResponse([
            'id' => $location->getId(),
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/log', name: 'get_log', methods: ['GET'])]
    public function getLocationLog(string $vehicleId): JsonResponse
    {
        $locations = $this->locationService->getLocationLog($vehicleId);

        $response = array_map(function ($location) {
            return [
                'id' => $location->getId(),
                'lat' => $location->getLat(),
                'lng' => $location->getLng(),
            ];
        }, $locations);

        return $this->json($response);
    }

    #[Route(path: '/geofence', name: 'get_geofence', methods: ['GET'])]
    public function getGeofence(string $vehicleId): JsonResponse
    {
        $geofence = $this->locationService->getGeofence($vehicleId);

        if (!$geofence) {
            return new JsonResponse(['error' => 'Geofence not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'lat' => $geofence->getLat(),
            'lng' => $geofence->getLng(),
            'radius' => $geofence->getRadius(),
        ]);
    }

    #[Route(path: '/geofence', name: 'set_geofence', methods: ['POST'])]
    public function setGeofence(string $vehicleId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['lat'], $data['lng'], $data['radius'])) {
            return new JsonResponse(['error' => 'Missing geofence data.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $geofence = $this->locationService->setGeofence(
            $vehicleId,
            $data['lat'],
            $data['lng'],
            $data['radius']
        );

        return new JsonResponse([
            'lat' => $geofence->getLat(),
            'lng' => $geofence->getLng(),
            'radius' => $geofence->getRadius(),
        ], JsonResponse::HTTP_CREATED);
    }
}
