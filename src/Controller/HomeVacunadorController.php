<?php

namespace App\Controller;

use App\Service\CustomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeVacunadorController extends AbstractController
{
    #[Route('/home/vacunador', name: 'app_home_vacunador')]
    public function index(
        CustomService $cs,
        Request $request, 
    ): Response
    {

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }




        return $this->render('home_vacunador/index.html.twig', [
            'controller_name' => 'HomeVacunadorController',
        ]);
    }
}
