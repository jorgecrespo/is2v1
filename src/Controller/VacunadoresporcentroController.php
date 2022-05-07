<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacunadoresporcentroController extends AbstractController
{
    #[Route('/vacunadoresporcentro', name: 'app_vacunadoresporcentro')]
    public function index(): Response
    {
        return $this->render('vacunadoresporcentro/index.html.twig', [
            'controller_name' => 'VacunadoresporcentroController',
        ]);
    }
}
