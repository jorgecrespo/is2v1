<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginPacienteController extends AbstractController
{
    #[Route('/login/paciente', name: 'app_login_paciente')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login_paciente/index.html.twig', [
            // 'controller_name' => 'LoginPacienteController',
            'last_username' => $lastUsername,
            'last_token' => '',
            'error'         => $error,
        ]);
    }
}
