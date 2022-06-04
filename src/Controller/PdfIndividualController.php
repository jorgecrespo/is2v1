<?php

namespace App\Controller;

use App\Entity\Aplicaciones;
use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PdfIndividualController extends AbstractController
{
    #[Route('/pdf/individual/{idVacuna}', name: 'app_pdf_individual')]
    public function index(
        int $idVacuna, 
        Pdf $knpSnappyPdf,
        CustomService $cs,
        ManagerRegistry $doctrine,

        )
    {
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail( $cs->getUser()['user']);
        $pacienteId = $paciente->getId();
        $vacuna = $em->getRepository(Vacunas::class)->findOneById($idVacuna);

        $turno = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, $vacuna->getId());
        
         $fecha_aplicacion = date_format($turno->getFecha(), "d-m-Y") ;
  
        
        $time = time();
        $filename = "CI-$pacienteId-$idVacuna-$time.pdf";
        $file_url = "pdf/" . $filename;

        // if (!file_exists($file_url)){

        $fecha_gen = date_format(new DateTime(), "d-m-Y") ;

        $nombre = $paciente->getNombre();
        
        $file =  $knpSnappyPdf->generateFromHtml(
            $this->renderView(
                'pdf_individual/index.html.twig',
                array(
                    'fecha_certificado'  => $fecha_gen,
                    'nombre' => $nombre,
                    'vacuna' => $vacuna->getNombre(),
                    'fecha' => $fecha_aplicacion,
                )
            ),
            $file_url
        );

    // }

        $response = new BinaryFileResponse ( $file_url );
        $response->headers->set ( 'Content-Type', 'application/pdf' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename );
        return $response;
   

        





    }
}
