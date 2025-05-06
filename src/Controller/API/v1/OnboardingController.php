<?php
declare(strict_types=1);

namespace App\Controller\API\v1;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\UserRepository;
use App\Response\HandledResponse;
use App\Response\RedirectResponse;
use App\Response\SeedResponse;
use App\Service\JsonNamedValueResolver;
use App\Service\SeedService;
use App\Service\VehicleCreationService;
use App\Struct\SerializedVehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/api/v1/onboarding',
    name: 'api.v1.onboarding'
)]
class OnboardingController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SeedService $seedService,
        private readonly VehicleCreationService $vehicleCreationService,
    ) {}
    #[Route(
        path: '/{user}',
        name: '.submit',
        methods: 'POST',
        format: 'json'
    )]
    public function submit(
        User $user,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $make,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $model,
        #[ValueResolver(JsonNamedValueResolver::class)] ?int $year,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $color,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $vin,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $licensePlates,
    ): JsonResponse{
        $this->vehicleCreationService->create(
            $user,
            $make,
            $model,
            $year,
            $color,
            $vin,
            $licensePlates
        );

        // Return a seed
        return new JsonResponse(
            $this->seedService->generate($user)
        );
    }

    #[Route(
        path: 'register',
        name: '.register',
        methods: 'POST',
        format: 'json'
    )]
    public function register(
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $firstName,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $lastName,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $email,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $phoneNum,
    ): JsonResponse {
        $user = $this->userRepository->findOneByEmail($email);
        if ($user){
            return new JsonResponse(
                new HandledResponse(
                    'Registration Error',
                    'A user with that email already exists.',
                )
            );
        }

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->phoneNum = $phoneNum;
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            new RedirectResponse(
                'Registration Successful!',
                'You may now log into SparkPlug.',
                $this->generateUrl('app')
            )
        );
    }
}