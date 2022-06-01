<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfGeneralController extends AbstractController
{
    #[Route('/pdf/general', name: 'app_pdf_general')]
    public function index(): Response
    {
        return $this->render('pdf_general/index.html.twig', [
            'controller_name' => 'PdfGeneralController',
        ]);
    }
}
