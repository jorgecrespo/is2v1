<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificacionesPacienteController extends AbstractController
{
    #[Route('/notificaciones/paciente', name: 'app_notificaciones_paciente')]
    public function index(): Response
    {
        return $this->render('notificaciones_paciente/index.html.twig', [
            'controller_name' => 'NotificacionesPacienteController',
        ]);
    }
}
