<?php

namespace App\Controller;

use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(
        Request $request,
        CustomService $cs,
    ): Response
    {

        $cs->logout();

        if (!$cs->validarUrl($request->getPathinfo())){
            return $this->redirect($cs->getHomePageByUser());
        }


        return $this->render('logout/index.html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}
