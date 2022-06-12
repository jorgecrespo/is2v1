<?php

namespace App\Controller;

use App\Entity\Aplicaciones;
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
        // $fechaGripe = $paciente->getVacunaGripeFecha();
        $fechaGripe = null;
        if ($fechaGripe == null){
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1) != null){

                $turnoGripe = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 1);
                $fechaGripe = $turnoGripe->getFecha();

                $loteGripe = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($turnoGripe->getId())->getLote();
                $aplicaciones[0] = array($fechaGripe, $loteGripe);
            } 
        }

        
        // fecha vacuna covid - 1
        $vacunasVacunaSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);
        // omito las vacunas previas del paciente
        // $fechaCovid1 = $paciente->getVacunaCovid1Fecha();
        $fechaCovid1 = null;
        if ($fechaCovid1 == null){
            if (count ($vacunasVacunaSistCovid) >0){
                
                $fechaCovid1 = $vacunasVacunaSistCovid[0]->getFecha();
                $loteCovid1 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunaSistCovid[0]->getId())->getLote();
                $aplicaciones[1] = array($fechaCovid1, $loteCovid1);
            } 
        }
        
        // fecha vacuna covid - 2

        // COMENTO PARA EXCLUIR VACUNAS PREVIAS A VACUNASIST
        // if ($paciente->getVacunaCovid2Fecha() != null){
        // $fechaCovid2 = $paciente->getVacunaCovid2Fecha();
        // } else if ( $paciente->getVacunaCovid1Fecha() != null and isset($vacunasVacunaSistCovid[0]) ){
        //     $fechaCovid2 = $vacunasVacunaSistCovid[0]->getFecha();
        // } else 
        
        if ( isset( $vacunasVacunaSistCovid[1]) ){
            $fechaCovid2 = $vacunasVacunaSistCovid[1]->getFecha();
            $loteCovid2 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunaSistCovid[1]->getId())->getLote();
            $aplicaciones[2] = array($fechaCovid2, $loteCovid2);

        } else {
            $fechaCovid2 = null;
        }


        // fecha vacuna Fiebre amarilla
        // $fechaFamarilla = $paciente->getVacunaHepatitisFecha();
        $fechaFamarilla = null;

        if ($fechaFamarilla == null){
            if ($em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3) != null){
                $turnoFamarilla = $em->getRepository(Turnos::class)->findOneByPacienteAndVacunaId($pacienteId, 3);
                $fechaFamarilla = $turnoFamarilla->getFecha();
                $loteFamarilla = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($turnoFamarilla->getId())->getLote();

                $aplicaciones[3] = array($fechaFamarilla,$loteFamarilla);
            } 
        }

        // dd($aplicaciones);

        $aplicacionesStr = array();

        // dd($aplicaciones);
        foreach($aplicaciones as $aplicacion){
            $fechaStr=null;
            if ($aplicacion != null ){
                $fechaStr = date_format($aplicacion[0], "d-m-Y");
            }
            $lote = $aplicacion != null ? $aplicacion[1]: null;
            array_push($aplicacionesStr, array($fechaStr ,$lote ));

        }
        // dd($aplicacionesStr);


        return $this->render('ver_historial_vacunas/index.html.twig', [
                      'vacunas' => $aplicacionesStr,

        ]);
    }
}
