<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginPacienteController extends AbstractController
{
    #[Route('/login/paciente', name: 'app_login_paciente')]
    public function index(): Response
    {
        return $this->render('login_paciente/index.html.twig', [
            'controller_name' => 'LoginPacienteController',
        ]);
    }
}
