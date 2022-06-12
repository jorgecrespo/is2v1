<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Usuarios;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacunasAAplicarController extends AbstractController
{
    #[Route('/vacunas/a/aplicar', name: 'app_vacunas_a_aplicar')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response
    {
        $em = $doctrine->getManager();
        $mail = $cs->getUser()['user'];
        $vacunador = $em->getRepository(Usuarios::class)->findOneByMail($mail);
        $vacunatorio_id = $vacunador->getVacunatorioId()->getId();
        $nombreVacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($vacunatorio_id)->getNombre();
        // dd($vacunador->getVacunatorioId(),$vacunador->getVacunatorioId()->getId() , $nombreVacunatorio);
        $turnos_pendientes = $em->getRepository(Turnos::class)->findTurnosByVacunatorio($vacunatorio_id);

        // dd($turnos_pendientes);
        $turnos = [];
        foreach($turnos_pendientes as $turno){
            
            $turnoStr['fecha'] = date_format($turno->getFecha(), "d-m-Y") ;

            if ($turnoStr['fecha'] == date_format(new DateTime(), "d-m-Y")){
                
                $turnoStr['id'] = $turno->getId();
                $turnoStr['paciente']= $em->getRepository(Pacientes::class)->findOneById($turno->getPacienteId())->getNombre();
                
                $turnoStr['vacuna'] = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId())->getNombre();
                $turnoStr['estado'] = $turno->getEstado();
                
                array_push($turnos, $turnoStr);
            }
        }


        return $this->render('vacunas_a_aplicar/index.html.twig', [
            'controller_name' => 'VacunasAAplicarController',
            'turnos'=> $turnos,
            'vacunador'=>$vacunador->getNombre(),
            'vacunatorio' => $nombreVacunatorio
        ]);
    }
}
