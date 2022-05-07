<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroPacienteController extends AbstractController
{
    #[Route('/registro/paciente', name: 'app_registro_paciente')]
    public function index(): Response
    {
        return $this->render('registro_paciente/index.html.twig', [
            'controller_name' => 'RegistroPacienteController',
        ]);
    }
}
