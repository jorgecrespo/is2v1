<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevVacunadoresController extends AbstractController
{
    #[Route('/dev/vacunadores', name: 'app_dev_vacunadores')]
    public function index(
        ManagerRegistry $doctrine,
    ): Response
    {
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
