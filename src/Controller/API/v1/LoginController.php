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
    path: '/api/v1/login',
    name: 'api.v1.login'
)]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SeedService $seedService,
    ) {}
    #[Route(
        path: '/authenticate',
        name: '.authenticate',
        methods: 'POST',
        format: 'json'
    )]
    public function authenticate(
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $email
    ): JsonResponse{
        $user = $this->userRepository->findOneByEmail($email);
        if ($user){
            return new JsonResponse(
                $this->seedService->generate($user)
            );
        }

        return new JsonResponse(
            new HandledResponse(
                'Login Error',
                'You could not be logged in.',
            )
        );
    }

    #[Route(
        path: '/register',
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