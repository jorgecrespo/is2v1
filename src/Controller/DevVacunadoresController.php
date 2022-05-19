<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevVacunadoresController extends AbstractController
{
    #[Route('/dev/vacunadores', name: 'app_dev_vacunadores')]
    public function index(
        ManagerRegistry $doctrine,
        CustomService $cs,
        Request $request, 
    ): Response
    {

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }


        $em = $doctrine->getManager();
        $usuarios = $em->getRepository(Usuarios::class)->findAll();

        //  dd($usuarios);
        // foreach($usuarios as &$usuario){
        //     $usuario->setPass(base64_decode($usuario->getPass()));
        // }

        return $this->render('dev_vacunadores/index.html.twig', [
            'controller_name' => 'DevVacunadoresController',
            'usuarios' => $usuarios

        ]);
    }
}
