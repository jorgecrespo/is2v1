<?php

namespace App\Controller;

use App\Entity\Aplicaciones;
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
        $lotes = array(null, null, null, null);

        // fecha vacuna gripe
       
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1) != null){
                $turnoGripe = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1);
                $fechaGripe = $turnoGripe->getFecha();
                $aplicaciones[0] = $fechaGripe;
                $lotes[0] = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($turnoGripe->getId())->getLote();

            } 
        
        
        $vacunasVacunasSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);
        // fecha vacuna covid - 1
      
            if (count ($vacunasVacunasSistCovid) >0){

                $fechaCovid1 = $vacunasVacunasSistCovid[0]->getFecha();
                $aplicaciones[1] = $fechaCovid1;
                $lotes[1] = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[0]->getId())->getLote();

            } 
        

        // fecha vacuna covid - 2
    if ( isset( $vacunasVacunasSistCovid[1]) ){
            $fechaCovid2 = $vacunasVacunasSistCovid[1]->getFecha();
            $lotes[2] = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[1]->getId())->getLote();

        } else {
            $fechaCovid2 = null;
            $aplicaciones[2] = $fechaCovid2;
        }

        // fecha vacuna Fiebre amarilla
 
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3) != null){
                $turnoFamarilla = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3);
                $fechaFamarilla = $turnoFamarilla->getFecha();
                $aplicaciones[3] = $fechaFamarilla;
                $lotes[3] = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($turnoFamarilla->getId())->getLote();

            } 
        

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
                    'nombre' => $paciente->getNombre() . " " . $paciente->getApellido(),
                    'vacunas' => $aplicacionesStr,
                    'fecha_certificado'  => $fecha_gen,
                    'lotes' => $lotes

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
