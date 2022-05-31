<?php

namespace App\Controller;

use App\Entity\Vacunatorios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
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
        ManagerRegistry $doctrine,

    ): Response
    {
        
        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

        $em = $doctrine->getManager();
        $data = $em->getRepository(Vacunatorios::class)->findAll();
        $nombres_vacunatorios = array($data[0]->getNombre(),$data[1]->getNombre(), $data[2]->getNombre());

        return $this->render('homeadmin/index.html.twig', [
            'controller_name' => 'HomeadminController',
            'nombres_vacunatorios' => $nombres_vacunatorios,
        ]);
    }
}
