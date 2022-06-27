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

class NotificacionesPendientesPacienteController extends AbstractController
{
    #[Route('/notificaciones/pendientes/paciente/{idNotificacion}', name: 'app_notificaciones_pendientes_paciente')]
    public function index(
        int $idNotificacion,
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response
    {

        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail( $cs->getUser()['user']);
        $turnos = $em->getRepository(Turnos::class)->findTurnosByUser( $paciente->getId());


        $notificacionesSinLeer = array();
        if( count($turnos) > 0 ){
            foreach($turnos as $turno){

               $notificaciones =  $em->getRepository(Notificaciones::class)->findNotificacionesByTurnoId($turno->getId());
               $vacuna = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId());
               $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId());

               if (count($notificaciones) > 0 ){
                foreach($notificaciones as $notificacion){
                    
                    if ( $notificacion->getLeida() == 0){
                        
                        $notifArray = array();
                        $diasAntelacino  = $notificacion->getAntelacion();

                        if (  $diasAntelacino > 0){

                            $fechaTurno = $turno->getFecha();
                            $fechaNotificacionStr = date("d-m-Y",strtotime(date_format($fechaTurno, "d-m-Y") ."- $diasAntelacino days"));
                            // dd($fechaNotificacionStr);
                            
                            $notifArray['fecha_notificacion'] = $fechaNotificacionStr;
                        } else {
                            $notifArray['fecha_notificacion'] ='x';
                        }
                        $notifArray['vacuna']  = $vacuna->getNombre();
                        $notifArray['vacunatorio']  = $vacunatorio->getNombre();
                        $notifArray['fecha_turno']  = date_format($turno->getFecha(), "d-m-Y");


                        
                        
                        array_push($notificacionesSinLeer, $notifArray);

                    }



                }


               }



            }
        }

        //  dd($notificacionesSinLeer, $idNotificacion);



        return $this->render('notificaciones_pendientes_paciente/index.html.twig', [
            'controller_name' => 'NotificacionesPendientesPacienteController',
            'notificaciones' => $notificacionesSinLeer
        ]);
    }
}
