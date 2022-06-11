<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class TurnoAsignadoController extends AbstractController
{
    #[Route('/turno/asignado', name: 'app_turno_asignado')]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        CustomService $cs,

    ): Response {
     
        $vacunatorioId = isset($request->request->All()['vacunatorio_id']) ? $request->request->All()['vacunatorio_id'] : null;
        
        $fecha_turno = isset($request->request->All()['turno_famarilla']) ? $request->request->All()['turno_famarilla'] : null;

        $fecha_seleccionada = new  DateTime  ( date($request->request->All()['turno_famarilla']));

        $hoy = date("d-m-Y");

        $fdesde = date("d-m-Y",strtotime($hoy ."+ 9 days"));
        $fdesdeMasUno = date("d-m-Y",strtotime($hoy ."+ 10 days"));
        $fecha_desde = new  DateTime ( $fdesde); 
        $fhasta = date("d-m-Y",strtotime($hoy ."+ 40 days"));
        $fecha_hasta = new  DateTime ( $fhasta); 
        


        $dif1 = (date_diff( $fecha_seleccionada, $fecha_desde)->invert != 0 );
        $dif2 = (date_diff( $fecha_seleccionada, $fecha_hasta)->invert == 0 );

        if (!$dif1 || !$dif2){

            $this->addFlash(type: 'error', message: 'El turno seleccionado debe estar entre el ' . $fdesdeMasUno . ' y ' . $fhasta );
            return $this->redirect('/asignacion/turno/famarilla');
        } 

        // dd($request, $vacunatorioId, $fecha_turno , $fecha_seleccionada,$fecha_desde, $dif1 , $fecha_hasta, $dif2, 'rta', $dif1 && $dif2 );

        $em = $doctrine->getManager();
        $turno = new Turnos();
        $mailPaciente = $cs->getUser()['user'];
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente);
        $turno->setPacienteId($paciente->getId());
        $vacuna = $em->getRepository(Vacunas::class)->findOneById(3);
        // dd($vacuna);
        $turno->setVacunaId($vacuna->getId());
        $turno->setEstado('ASIGNADO');
        $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($vacunatorioId);
        // dd($vacunatorioId, $vacunatorio);
        $turno->setVacunatorioId($vacunatorio->getID());
        $turno->setFecha(($fecha_seleccionada));

        $em->persist($turno);
        $em->flush();

        $turnoX['fecha'] = date_format($fecha_seleccionada, "d-m-Y") ;
        $turnoX['vacunatorio'] = $vacunatorio->getNombre();
        $turnoX['direccion'] = $vacunatorio->getDireccion();
        $turnoX['vacuna'] = $vacuna->getNombre();

        return $this->render('turno_asignado/index.html.twig', [
            'controller_name' => 'TurnoAsignadoController',
            'vacunatorio_id' => $vacunatorioId,
            'turno' => $turnoX,
        ]);
    }
}
