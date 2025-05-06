<?php
declare(strict_types=1);

namespace App\Controller\API\v1;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Service\JsonNamedValueResolver;
use App\Service\VehicleCreationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/vehicle', 'api.v1.vehicle')]
class VehicleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    #[Route(path: '/{vehicle}', name: '.delete', methods: 'DELETE', format: 'json')]
    public function delete(
        Vehicle $vehicle
    ){
        if ($vehicle->geofence){
            $this->entityManager->remove($vehicle->geofence);
            $vehicle->geofence = null;
            $this->entityManager->flush();
        }

        $this->entityManager->remove($vehicle);
        $this->entityManager->flush();
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}