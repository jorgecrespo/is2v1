<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

class PdfIndividualController extends AbstractController
{
    #[Route('/pdf/individual', name: 'app_pdf_individual')]
    public function index(Pdf $pdf)
    {

        $html = $this->renderView('pdf_individual/index.html.twig', [
                'controller_name' => 'PdfIndividualController',
            ]);
        $filename = 'certificado.pdf';
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            $filename
        );




        // return $this->render('pdf_individual/index.html.twig', [
        //     'controller_name' => 'PdfIndividualController',
        // ]);
    }
}
