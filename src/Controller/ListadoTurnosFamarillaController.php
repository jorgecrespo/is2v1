<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ListadoTurnosFamarillaController extends AbstractController
{
    #[Route('/listado/turnos/famarilla', name: 'app_listado_turnos_famarilla')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
        Request $request,

    ): Response
    {
        $em = $doctrine->getManager();


        if ( isset ($request->request->all()["id_baja_turno"])){

            $turnoId = $request->request->all()["id_baja_turno"];
            $em->getRepository(Turnos::class)->bajaTurno($turnoId);
        
        }
   


        $turnos_pendientes = $em->getRepository(Turnos::class)->findTurnosByVacuna(3);

        // dd($turnos_pendientes);
        $turnos = [];
        foreach($turnos_pendientes as $turno){

            $turnoStr['id'] = $turno->getId();
            $turnoStr['paciente']= $em->getRepository(Pacientes::class)->findOneById($turno->getPacienteId())->getNombre();

            $turnoStr['fecha'] = date_format($turno->getFecha(), "d-m-Y") ;
            $turnoStr['estado'] = $turno->getEstado();
            
            $hoy =  new DateTime();
        //    $fturno = $turno->getFecha();

        // TODO: actualizar turnos vencidos a cancelados con alguna funcion que corra periodicamente
        // TODO: notificar al usuario por mail
            if ( ( date_diff( $hoy, $turno->getFecha())->invert != 0 )and ($turnoStr['estado'] == 'ASIGNADO'))
            {
                $turnoStr['estado'] = 'CANCELADO';
            }
            // $turnoStr['estado'] = date_diff( $hoy, $turno->getFecha())->invert;

            array_push($turnos, $turnoStr);
        }





        return $this->render('listado_turnos_famarilla/index.html.twig', [
            'controller_name' => 'ListadoTurnosFamarillaController',
            'turnos' => $turnos,
        ]);
    }
}
