<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnconstruccionController extends AbstractController
{
    #[Route('/enconstruccion', name: 'app_enconstruccion')]
    public function index(): Response
    {
        return $this->render('enconstruccion/index.html.twig', [
            'controller_name' => 'EnconstruccionController',
        ]);
    }
}
