<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class UsuariosController extends AbstractController
{
    #[Route('/usuarios', name: 'app_usuarios')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $em = $doctrine->getManager();
        $usuarios = $em->getRepository(Usuarios::class)->findAll();



        return $this->render('usuarios/index.html.twig', [
            'controller_name' => 'UsuariosController',
            'usuarios'=> $usuarios,
        ]);
    }
}
