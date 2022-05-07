<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModificarvacunadorController extends AbstractController
{
    #[Route('/modificarvacunador', name: 'app_modificarvacunador')]
    public function index(): Response
    {
        return $this->render('modificarvacunador/index.html.twig', [
            'controller_name' => 'ModificarvacunadorController',
        ]);
    }
}
