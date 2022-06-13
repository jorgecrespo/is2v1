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
        $lote1 = null;
        $lote2 = null;

        if ($idVacuna != 2) {


                $turno = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, $vacuna->getId());
            if ($turno != null){
                $fecha_aplicacion = date_format($turno->getFecha(), "d-m-Y");
                $lote1 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($turno->getId())->getLote();
            }

        } else {
            $fecha_aplicacion = null;
            $vacunasVacunasSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);
            if ($fecha_aplicacion == null) {
                if (count($vacunasVacunasSistCovid) > 0) {

                    $fecha_aplicacion = $vacunasVacunasSistCovid[0]->getFecha();
                    $fecha_aplicacion = date_format($fecha_aplicacion, "d-m-Y");
                    $lote1 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[0]->getId())->getLote();
                }
            } 

            if (isset($vacunasVacunasSistCovid[1])) {
                $fechaCovid2 = $vacunasVacunasSistCovid[1]->getFecha();
                $lote2 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[1]->getId())->getLote();

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

        $nombre = $paciente->getNombre() . ' ' .  $paciente->getApellido();

        $file =  $knpSnappyPdf->generateFromHtml(
            $this->renderView(
                'pdf_individual/index.html.twig',
                array(
                    'fecha_certificado'  => $fecha_gen,
                    'nombre' => $nombre,
                    'vacuna' => $vacuna->getNombre(),
                    'fecha' => $fecha_aplicacion,
                    'segunda' => $fechaSegunda,
                    'lote1' => $lote1,
                    'lote2' => $lote2,
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
