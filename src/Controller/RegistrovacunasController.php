<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrovacunasController extends AbstractController
{
    #[Route('/registrovacunas', name: 'app_registrovacunas')]
    public function index(): Response
    {
        return $this->render('registrovacunas/index.html.twig', [
            'controller_name' => 'RegistrovacunasController',
        ]);
    }
}
