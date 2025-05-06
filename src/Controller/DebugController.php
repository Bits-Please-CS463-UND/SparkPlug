<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DebugController extends AbstractController
{
    //define new route   
    #[Route('/debug-panel',name:'debug_panel')]
    public function index():Response
    {
        //collect useful info for debugging
        $debug=[
            'php_version'=>phpversion(),
            'symfony_version'=>\Symfony\Component\HttpKernel\Kernel::VERSION,
            'env'=> $this->getParameter('kernel.environment'),
            'log'=>file_exists($this->getParameter('kernel.project_dir').'/var/log/dev.log')
            ? file_get_contents($this->getParameter('kernel.project_dir').'/var/log/dev.log')
            :'No Logs Available. '
            ];
        
        return $this->render('views/debug.html.twig',
        ['debug'=>$debug]);
    }

    #[Route(
        path: "/notifications",
        name: "notifications",
        methods: ["GET"]
    )]
    #[Template(
        template: "/views/vehicle_not.html.twig"
    )]
    public function root(){}
}
