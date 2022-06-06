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

    ) {
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail($cs->getUser()['user']);
        $pacienteId = $paciente->getId();
        $vacuna = $em->getRepository(Vacunas::class)->findOneById($idVacuna);

        $fechaSegunda = null;


        if ($idVacuna != 2) {


            if ($paciente->getVacunaGripeFecha() != null)
                $turno = $paciente->getVacunaGripeFecha();
            else{
                $turno = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, $vacuna->getId());
            }
            if ($turno != null)
            $fecha_aplicacion = date_format($turno, "d-m-Y");

        } else {
            $fecha_aplicacion = null;
            $vacunasVacunaSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);
            // fecha vacuna covid - 1
            $fecha_aplicacion = $paciente->getVacunaCovid1Fecha();
            if ($fecha_aplicacion == null) {
                if (count($vacunasVacunaSistCovid) > 0) {

                    $fecha_aplicacion = $vacunasVacunaSistCovid[0]->getFecha();
                }
            } 
            $fecha_aplicacion = date_format($fecha_aplicacion, "d-m-Y");

            // fecha vacuna covid - 2
            if ($paciente->getVacunaCovid2Fecha() != null) {
                $fechaCovid2 = $paciente->getVacunaCovid2Fecha();
            } else if ($paciente->getVacunaCovid1Fecha() != null and isset($vacunasVacunaSistCovid[0])) {
                $fechaCovid2 = $vacunasVacunaSistCovid[0]->getFecha();
            } else if (isset($vacunasVacunaSistCovid[1])) {
                $fechaCovid2 = $vacunasVacunaSistCovid[1]->getFecha();
            } else {
                $fechaCovid2 = null;
            }

            $fechaSegunda = $fechaCovid2 != null? date_format($fechaCovid2, "d-m-Y") : null;
            //  dd($fecha_aplicacion, $fechaSegunda);
        }

        if (($idVacuna != 2 and $turno == null) or ($idVacuna == 2 and $fecha_aplicacion == null)) {
            $this->addFlash(type: 'error', message: 'Ud. no tiene aplicada esta vacuna');
            return $this->redirect($cs->getHomePageByUser());
        }




        $time = time();
        $filename = "CI-$pacienteId-$idVacuna-$time.pdf";
        $file_url = "pdf/" . $filename;

        // if (!file_exists($file_url)){

        $fecha_gen = date_format(new DateTime(), "d-m-Y");

        $nombre = $paciente->getNombre();

        $file =  $knpSnappyPdf->generateFromHtml(
            $this->renderView(
                'pdf_individual/index.html.twig',
                array(
                    'fecha_certificado'  => $fecha_gen,
                    'nombre' => $nombre,
                    'vacuna' => $vacuna->getNombre(),
                    'fecha' => $fecha_aplicacion,
                    'segunda' => $fechaSegunda,
                )
            ),
            $file_url
        );

        // }

        $response = new BinaryFileResponse($file_url);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        return $response;
    }
}
