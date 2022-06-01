<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerTurnosPendientesController extends AbstractController
{
    #[Route('/ver/turnos/pendientes', name: 'app_ver_turnos_pendientes')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine, 

    ): Response
    {
        
        $em = $doctrine->getManager();
        $mailPaciente = $cs->getUser()['user'];
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente);

        $turnosDelPaciente = $em->getRepository(Turnos::class)->findTurnosByUser($paciente->getId());
        $turnos = [];
        foreach($turnosDelPaciente as $turno){
            if ($turno->getEstado() == 'ASIGNADO'){
                $turnoStr['vacuna'] = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId())->getNombre();
                $turnoStr['fecha'] = date_format($turno->getFecha(), "d-m-Y") ;
                $turnoStr['vacunatorio'] = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId())->getNombre();
                // dd($turno, $turnoStr);
                array_push($turnos, $turnoStr);
            }
        }
        // dd($turnos);

        return $this->render('ver_turnos_pendientes/index.html.twig', [
            'controller_name' => 'VerTurnosPendientesController',
            'nombre_paciente' => $paciente->getApellido() . ', ' . $paciente->getNombre(),
            'turnos' => $turnos,
        ]);
    }
}
