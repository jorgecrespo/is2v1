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
        $vacunasVacunasSistCovid = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($pacienteId, 2);
        // omito las vacunas previas del paciente
        $fechaCovid1 = null;
        if ($fechaCovid1 == null){
            if (count ($vacunasVacunasSistCovid) >0){
                
                $fechaCovid1 = $vacunasVacunasSistCovid[0]->getFecha();
                $loteCovid1 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[0]->getId())->getLote();
                $aplicaciones[1] = array($fechaCovid1, $loteCovid1);
            } 
        }
        
        // fecha vacuna covid - 2
        
        if ( isset( $vacunasVacunasSistCovid[1]) ){
            $fechaCovid2 = $vacunasVacunasSistCovid[1]->getFecha();
            $loteCovid2 = $em->getRepository(Aplicaciones::class)->findOneByTurnoId($vacunasVacunasSistCovid[1]->getId())->getLote();
            $aplicaciones[2] = array($fechaCovid2, $loteCovid2);

        } else {
            $fechaCovid2 = null;
        }


        // fecha vacuna Fiebre amarilla
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
            } else {
                $fechaStr ="Vacuna no aplicada";
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
