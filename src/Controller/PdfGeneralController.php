<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class PdfGeneralController extends AbstractController
{
    #[Route('/pdf/general', name: 'app_pdf_general')]
    public function index(
        Pdf $knpSnappyPdf,
        CustomService $cs,
        ManagerRegistry $doctrine,
    )
    {
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail( $cs->getUser()['user']);
        $pacienteId = $paciente->getId();


        $aplicaciones = array(null, null, null, null);

        // fecha vacuna gripe
        $fechaGripe = $paciente->getVacunaGripeFecha();
        if ($fechaGripe == null){
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1) != null){

                $fechaGripe = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1)->getFecha();
            } 
        }
        $aplicaciones[0] = $fechaGripe;
        $vacunasVacunasSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);

        // fecha vacuna covid - 1
        $fechaCovid1 = $paciente->getVacunaCovid1Fecha();
        if ($fechaCovid1 == null){
            if (count ($vacunasVacunasSistCovid) >0){

                $fechaCovid1 = $vacunasVacunasSistCovid[0]->getFecha();
            } 
        }
        $aplicaciones[1] = $fechaCovid1;

        // fecha vacuna covid - 2
        if ($paciente->getVacunaCovid2Fecha() != null){
        $fechaCovid2 = $paciente->getVacunaCovid2Fecha();

        } else if ( $paciente->getVacunaCovid1Fecha() != null and isset($vacunasVacunasSistCovid[0]) ){
            $fechaCovid2 = $vacunasVacunasSistCovid[0]->getFecha();
        } else if ( isset( $vacunasVacunasSistCovid[1]) ){
            $fechaCovid2 = $vacunasVacunasSistCovid[1]->getFecha();
        } else {
            $fechaCovid2 = null;
        }
        $aplicaciones[2] = $fechaCovid2;

        // fecha vacuna Fiebre amarilla
        $fechaFamarilla = $paciente->getVacunaHepatitisFecha();
        if ($fechaFamarilla == null){
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3) != null){

                $fechaFamarilla = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3)->getFecha();
            } 
        }
        $aplicaciones[3] = $fechaFamarilla;

        // dd($aplicaciones);

        $aplicacionesStr = array();

        $sinVacunas  = true;
        foreach($aplicaciones as $aplicacion){

            if ($aplicacion != null ){

                $aplicacionStr =  date_format($aplicacion, "d-m-Y") ;
                $sinVacunas = false;
            } else {
                $aplicacionStr = null;
            }


            array_push($aplicacionesStr, $aplicacionStr );

        }

      
        if ($sinVacunas){

            $this->addFlash(type: 'error', message: 'Ud. no registra vacunas aplicadas');
            return $this->redirect($cs->getHomePageByUser());
            
                
                // dd($aplicacionesStr);
            } else {
        $time = time();

        $filename = "CG-$pacienteId-$time.pdf";
        $file_url = "pdf/" . $filename;

        $fecha_gen = date_format(new DateTime(), "d-m-Y") ;

        $nombre = $paciente->getNombre();
        
        $fecha_gen = date_format(new DateTime(), "d-m-Y") ;


        $file =  $knpSnappyPdf->generateFromHtml(
            $this->renderView(
                'pdf_general/index.html.twig',
                array(
                    'nombre' => $paciente->getNombre(),
                    'vacunas' => $aplicacionesStr,
                    'fecha_certificado'  => $fecha_gen,

                )
            ),
            $file_url
        );


        $response = new BinaryFileResponse ( $file_url );
        $response->headers->set ( 'Content-Type', 'application/pdf' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename );
        return $response;
    }
    }
}
