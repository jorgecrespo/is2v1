<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AltapacienteController extends AbstractController
{
    #[Route('/altapaciente', name: 'app_altapaciente')]
    public function index(): Response
    {
        return $this->render('altapaciente/index.html.twig', [
            'controller_name' => 'AltapacienteController',
        ]);
    }
}
