<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\VacunasType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompletarVacunacionController extends AbstractController
{
    #[Route('/completar/vacunacion', name: 'app_completar_vacunacion')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
        Request $request, 

    ): Response
    {
        
        $mail = $cs->getUser()['user'];
        
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByMail($mail);

        $form = $this->createForm(VacunasType::class, $paciente,  [
            'attr' => ['id'=>'form_vacunas']
            ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
         
            // $em->persist($paciente);
            $em->flush();
            $this->addFlash(type: 'success', message:'Vacunas registradas exitosamente.');
            return $this->redirectToRoute( route : 'app_homepaciente');
        }



        return $this->render('completar_vacunacion/index.html.twig', [
            'controller_name' => 'CompletarVacunacionController',
            'formulario'      => $form->createView()
        ]);
    }
}
