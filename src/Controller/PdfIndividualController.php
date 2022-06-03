<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PdfIndividualController extends AbstractController
{
    #[Route('/pdf/individual', name: 'app_pdf_individual')]
    public function index(Pdf $knpSnappyPdf)
    {


        $file_url = 'pdf/file5.pdf';

        $displayName='pepe.pdf';
        $file =  $knpSnappyPdf->generateFromHtml(
            $this->renderView(
                'pdf_individual/index.html.twig',
                // array(
                //     'some'  => $vars
                // )
            ),
            $file_url
        );


        $response = new BinaryFileResponse ( $file_url );
        $response->headers->set ( 'Content-Type', 'application/pdf' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName );
        return $response;
   

        





    }
}
