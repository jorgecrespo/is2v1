<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeadminController extends AbstractController
{
    #[Route('/homeadmin', name: 'app_homeadmin')]
    public function index(): Response
    {
        return $this->render('homeadmin/index.html.twig', [
            'controller_name' => 'HomeadminController',
        ]);
    }
}
