<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeVacunadorController extends AbstractController
{
    #[Route('/home/vacunador', name: 'app_home_vacunador')]
    public function index(): Response
    {
        return $this->render('home_vacunador/index.html.twig', [
            'controller_name' => 'HomeVacunadorController',
        ]);
    }
}
