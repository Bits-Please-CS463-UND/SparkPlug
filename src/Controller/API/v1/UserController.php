<?php
declare(strict_types=1);

namespace App\Controller\API\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: "/api/v1/user",
    name: "api.v1.user"
)]
class UserController extends AbstractController
{

}