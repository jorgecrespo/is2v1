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

        $em = $doctrine->getManager();
        $turno = new Turnos();
        $mailPaciente = $cs->getUser()['user'];
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente);
        $turno->setPacienteId($paciente);
        $vacuna = $em->getRepository(Vacunas::class)->findOneById(3);
        // dd($vacuna);
        $turno->setVacunaId($vacuna);
        $turno->setEstado('ASIGNADO');
        $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($vacunatorioId);
        // dd($vacunatorioId, $vacunatorio);
        $turno->setVacunatorioId($vacunatorio);
        $hoy = date("d-m-Y");
        $fechaTurno = date("d-m-Y", strtotime($hoy . "+ 10 days"));
        $turno->setFecha(new Datetime($fechaTurno));

        $em->persist($turno);
        $em->flush();

        return $this->render('turno_asignado/index.html.twig', [
            'controller_name' => 'TurnoAsignadoController',
            'vacunatorio_id' => $vacunatorioId,
        ]);
    }
}
