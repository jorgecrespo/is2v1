<?php

namespace App\Controller;

use App\Service\CustomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeadminController extends AbstractController
{
    #[Route('/homeadmin', name: 'app_homeadmin')]
    public function index(
        CustomService $cs,
        Request $request,

    ): Response
    {
        
        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }



        return $this->render('homeadmin/index.html.twig', [
            'controller_name' => 'HomeadminController',
        ]);
    }
}
