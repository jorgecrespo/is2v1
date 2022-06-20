<?php

namespace App\Controller;

use App\Entity\Notificaciones;
use App\Entity\Pacientes;
use App\Entity\Turnos;
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
               if (count($notificaciones) > 0 ){
                foreach($notificaciones as $notificacion){
                    
                    if ( $notificacion->getLeida() == 0){
                        array_push($notificacionesSinLeer, $notificacion);
                        



                    }



                }


               }



            }
        }

        // dd($notificacionesSinLeer, $idNotificacion);



        return $this->render('notificaciones_pendientes_paciente/index.html.twig', [
            'controller_name' => 'NotificacionesPendientesPacienteController',
        ]);
    }
}
