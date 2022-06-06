<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Switch_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AsignacionTurnoController extends AbstractController
{
    #[Route('/asignacion/turno/{tipo}', name: 'app_asignacion_turno')]
    public function index(
        string $tipo,
        ManagerRegistry $doctrine,
        CustomService $cs,

        ): Response
    {

        switch ($tipo){
            case 'gripe':
                $enfermedad = 'Gripe';
                break;
            case 'covid':
                $enfermedad = 'Covid-19';
                break;
            case 'famarilla':
                $enfermedad = "Fiebre Amarilla";
                break;
        }

        $em = $doctrine->getManager();
        $mailPaciente = $cs->getUser()['user'];
        $paciente = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente);


        // dd($paciente->getId());
        $turnosGripePendiente = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($paciente->getId(), 3,  'ASIGNADO');
        // dd($turnosGripePendiente);
        $turnosGripeAplicada = $em->getRepository(Turnos::class)->findTurnosByPacienteAndVacunaId($paciente->getId(), 3);

        if (count($turnosGripePendiente) >0 ){
            $this->addFlash(type: 'error', message: 'Ud. ya tiene un turno para aplicar la vacuna contra la fiebre amarilla');
            return $this->redirect($cs->getHomePageByUser());

        }

        if ( count($turnosGripeAplicada) > 0){
            $this->addFlash(type: 'error', message: 'Ud. ya tiene aplicada la vacuna contra la fiebre amarilla');
            return $this->redirect($cs->getHomePageByUser());

        }



        
        // $turnosPaciente = $em->getRepository(Turnos::class)->findTurnosByUser($paciente->getId());
        // dd($turnosPaciente);


        $vacunatorios = $em->getRepository(Vacunatorios::class)->findAll();
        // dd($vacunatorios);
        // $mailPaciente = $cs->getUser()['user'];
        // $feNac = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente)->getFechaNac();
        // $edad = $cs->getEdad($feNac);
        
        // $yaVacunado = (bool) $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente)->getVacunaHepatitisFecha() != null;
        // // dd($yaVacunado);





        return $this->render('asignacion_turno/index.html.twig', [
            'controller_name' => 'AsignacionTurnoController',
            'enf' => $enfermedad,
            'vacunatorios'=> $vacunatorios,

        ]);
    }
}
