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
        // Fill in vehicle details
        $vehicle = new Vehicle();
        $vehicle->make = $make;
        $vehicle->model = $model;
        $vehicle->year = $year;
        $vehicle->color = $color;
        $vehicle->vin = $vin;
        $vehicle->licensePlates = $licensePlates;
        $vehicle->owner = $user;

        // Add association to owner
        $vehicle->drivers->add($user);
        $user->ownedVehicles->add($vehicle);
        $user->sharedVehicles->add($vehicle);

        // Write out
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

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