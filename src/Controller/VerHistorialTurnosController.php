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

class VerHistorialTurnosController extends AbstractController
{
    #[Route('/ver/historial/turnos', name: 'app_ver_historial_turnos')]
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
                $turnoStr['vacuna'] = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId()->getId())->getNombre();
                $turnoStr['fecha'] = date_format($turno->getFecha(), "d-m-Y") ;
                $turnoStr['vacunatorio'] = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId()->getId())->getNombre();
                $turnoStr['estado'] = $turno->getEstado();
                
                array_push($turnos, $turnoStr);
        }


        return $this->render('ver_historial_turnos/index.html.twig', [
            'controller_name' => 'VerHistorialTurnosController',
            'nombre_paciente' => $paciente->getApellido() . ', ' . $paciente->getNombre(),
            'turnos' => $turnos,
        ]);
    }
}
