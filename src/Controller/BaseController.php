<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Vehicle;
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
        template: "/views/index.html.twig"
    )]
    public function root(){}

    #[Route(
        path: "/app",
        name: "app",
        methods: ["GET"]
    )]
    #[Template(
        template: "/views/app.html.twig"
    )]
    public function app(){}

    #[Route(
        path: "/location/{vehicle}",
        name: 'bosshog',
        methods: 'GET'
    )]
    #[Template(
        template: '/views/location.html.twig',
        vars: ['vehicle']
    )]
    public function setLocation(Vehicle $vehicle){}
}