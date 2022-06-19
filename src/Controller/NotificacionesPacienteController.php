<?php

namespace App\Controller;

use App\Entity\Notificaciones;
use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificacionesPacienteController extends AbstractController
{
    #[Route('/notificaciones/paciente', name: 'app_notificaciones_paciente')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response
    {

        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail( $cs->getUser()['user']);
        $turnos = $em->getRepository(Turnos::class)->findTurnosByUser( $paciente->getId());
        
        $listaNotificacinoes = array();
        
        if (count($turnos) > 0){
            
            foreach($turnos as $turno){
                
                $vacuna = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId());
                $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId());
                
                $notificaciones = $em->getRepository(Notificaciones::class)->findNotificacionesByTurnoId( $turno->getId());
                
                if (count ( $notificaciones) >0 ){

                    foreach($notificaciones as $notificacion){


                        $diasAntelacino = $notificacion->getAntelacion();
                        $fechaNotificacionStr = $turno->getFecha();
                        $fechaNotificacionStr = $fdesde = date("d-m-Y",strtotime(date_format($fechaNotificacionStr, "d-m-Y") ."- $diasAntelacino days"));


                        $itemNotificacion = array (
                            'fecha_turno' => date_format($turno->getFecha(), "d-m-Y") ,
                            'fecha_notificacion' =>$fechaNotificacionStr ,
                            'vacuna' => $vacuna->getNombre(),
                            'vacunatorio' => $vacunatorio->getNombre()
                        );
                            
                            array_push($listaNotificacinoes, $itemNotificacion);
                        }
                        
                    }
                
                
            }
        }

        // dd($listaNotificacinoes);

        return $this->render('notificaciones_paciente/index.html.twig', [
            'controller_name' => 'NotificacionesPacienteController',
            'notificaciones' => $listaNotificacinoes,
        ]);
    }
}
