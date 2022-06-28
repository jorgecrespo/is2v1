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

class LeerNotificacionController extends AbstractController
{
    #[Route('/leer/notificacion/{idNotificacion}', name: 'app_leer_notificacion')]
    public function index(
        int $idNotificacion,
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response {

        $em = $doctrine->getManager();

        $notificacion = $em->getRepository(Notificaciones::class)->findOneById($idNotificacion);
        $turno = $em->getRepository(Turnos::class)->findOneById($notificacion->getTurnoId());

        $vacuna = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId());
        $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId());


        $notifSinLeer = array();

        $diasAntelacino  = $notificacion->getAntelacion();

        if ($diasAntelacino > 0) {

            $fechaTurno = $turno->getFecha();
            $fechaNotificacionStr = date("d-m-Y", strtotime(date_format($fechaTurno, "d-m-Y") . "- $diasAntelacino days"));

            $notifSinLeer['fecha_notificacion'] = $fechaNotificacionStr;
        } else {
            $notifSinLeer['fecha_notificacion'] = date_format($turno->getFechaBaja(), "d-m-Y");;
            // dd($notifSinLeer);
        }
        $notifSinLeer['vacuna']  = $vacuna->getNombre();
        $notifSinLeer['vacunatorio']  = $vacunatorio->getNombre();
        $notifSinLeer['fecha_turno']  = date_format($turno->getFecha(), "d-m-Y");
        $mensaje = '';
        if ($diasAntelacino == 1){
        $mensaje = 'mensaje aviso turno para el dia siguiente';
        } else {
            $mensaje = 'mensaje aviso cancelacion de turno';
        }

        $notificacion->setLeida(1);
        $em->flush();

        
        $notifSinLeer['mensaje']  = $mensaje;

        // dd($notifSinLeer);

        return $this->render('leer_notificacion/index.html.twig', [
            'controller_name' => 'LeerNotificacionController',
            'notif' => $notifSinLeer
        ]);
    }
}
