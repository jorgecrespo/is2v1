<?php

namespace App\Controller;

use App\Entity\Aplicaciones;
use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Form\AplicacionVacunaType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrarVacunacionController extends AbstractController
{
    #[Route('/registrar/vacunacion/{turno_id}', name: 'app_registrar_vacunacion')]
    public function index(
        int $turno_id,
        CustomService $cs,
        ManagerRegistry $doctrine,
        Request $request,
        ): Response
    {

        $em = $doctrine->getManager();
        $turno = $em->getRepository(Turnos::class)->findOneById($turno_id);
        $paciente = $em->getRepository(Pacientes::class)->findOneById($turno->getPacienteId());
        $vacuna = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId());
        // dd($paciente);

        $aplicacion = new Aplicaciones();
        // $aplicacion->setTurnoId($turno->getId());
        
        $form = $this->createForm(AplicacionVacunaType::class, $aplicacion);

        
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $aplicacion->setTurnoId($turno);
            $em->persist($aplicacion);
            $turno->setEstado('APLICADA');
            $em->flush();
            $this->addFlash(type: 'success', message: 'Aplicacion registrada correctamente.');
            return $this->redirect('/vacunas/a/aplicar');



        }



        return $this->render('registrar_vacunacion/index.html.twig', [
            'controller_name' => 'RegistrarVacunacionController',
            'turno'=> $turno_id,
            'formulario'      => $form->createView(),
            'paciente' => $paciente->getNombre(),
            'vacuna' => $vacuna->getNombre(),

        ]);
    }
}
