<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminvacunadoresController extends AbstractController
{
    #[Route('/adminvacunadores', name: 'app_adminvacunadores')]
    public function index(): Response
    {
        return $this->render('adminvacunadores/index.html.twig', [
            'controller_name' => 'AdminvacunadoresController',
        ]);
    }
}
