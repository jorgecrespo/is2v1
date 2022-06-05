<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerHistorialVacunasController extends AbstractController
{
    #[Route('/ver/historial/vacunas', name: 'app_ver_historial_vacunas')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response
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
        $vacunasVacunaSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);

        // fecha vacuna covid - 1
        $fechaCovid1 = $paciente->getVacunaCovid1Fecha();
        if ($fechaCovid1 == null){
            if (count ($vacunasVacunaSistCovid) >0){

                $fechaCovid1 = $vacunasVacunaSistCovid[0]->getFecha();
            } 
        }
        $aplicaciones[1] = $fechaCovid1;

        // fecha vacuna covid - 2
        if ($paciente->getVacunaCovid2Fecha() != null){
        $fechaCovid2 = $paciente->getVacunaCovid2Fecha();

        } else if ( $paciente->getVacunaCovid1Fecha() != null and isset($vacunasVacunaSistCovid[0]) ){
            $fechaCovid2 = $vacunasVacunaSistCovid[0]->getFecha();
        } else if ( isset( $vacunasVacunaSistCovid[1]) ){
            $fechaCovid2 = $vacunasVacunaSistCovid[1]->getFecha();
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

        foreach($aplicaciones as $aplicacion){

            array_push($aplicacionesStr, $aplicacion != null ? date_format($aplicacion, "d-m-Y")  : null );

        }


        return $this->render('ver_historial_vacunas/index.html.twig', [
                      'vacunas' => $aplicacionesStr,

        ]);
    }
}
