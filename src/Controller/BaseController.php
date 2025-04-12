<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/")]
class BaseController extends AbstractController
{
    #[Route(
        path: "/",
        name: "root",
        methods: ["GET"]
    )]
    #[Template(
        template: "/views/debug.html.twig"
    )]
    public function root(){}
}