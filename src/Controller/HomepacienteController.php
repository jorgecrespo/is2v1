<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepacienteController extends AbstractController
{
    #[Route('/homepaciente', name: 'app_homepaciente')]
    public function index(): Response
    {
        return $this->render('homepaciente/index.html.twig', [
            'controller_name' => 'HomepacienteController',
        ]);
    }
}
