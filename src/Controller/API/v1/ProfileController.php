<?php
declare(strict_types=1);

namespace App\Controller\API\v1;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Response\HandledResponse;
use App\Response\RedirectResponse;
use App\Service\JsonNamedValueResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/profile/{user}', 'api.v1.profile')]
class ProfileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    ){}

    #[Route(path: '', name: '.set', methods: ['POST'])]
    public function setProfile(
        User $user,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $firstName,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $lastName,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $email,
        #[ValueResolver(JsonNamedValueResolver::class)] ?string $phoneNum,
    ): JsonResponse
    {
        // Ensure there's no email collisions
        $existingUser = $this->userRepository->findOneByEmail($email);
        if ($existingUser instanceof User && $existingUser->id !== $user->id) {
            return new JsonResponse(
                new HandledResponse(
                    'Uh Oh!',
                    'A user with that email already exists.'
                )
            );
        }

        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->phoneNum = $phoneNum;
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse(
            new RedirectResponse(
                'Profile Updated!',
                'The app will now reload to reflect this change.',
                $this->generateUrl('app')
            )
        );
    }
}
