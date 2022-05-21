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

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }
        
        $mail = $cs->getUser()['user'];
        
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByMail($mail);

        $form = $this->createForm(VacunasType::class, $paciente,  [
            'attr' => ['id'=>'form_vacunas']
            ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
         
            // dd($form['vacuna_gripe_fecha']->getData()
            // ,$form['vacuna_covid1_fecha']->getData()
            // ,$form['vacuna_covid2_fecha']->getData()
            // ,$form['vacuna_hepatitis_fecha']->getData());
            if ($form['vacuna_covid1_fecha']->getData() == null && $form['vacuna_covid2_fecha']->getData() !=null){
                $this->addFlash(type: 'error', message: 'Debe ingresar la fecha de vacunacion de la Dosis 1 de Covid-19');

            } else{

                // $em->persist($paciente);
                $em->flush();
                $this->addFlash(type: 'success', message:'Vacunas registradas exitosamente.');
                return $this->redirectToRoute( route : 'app_homepaciente');
            }
        }



        return $this->render('completar_vacunacion/index.html.twig', [
            'formulario'      => $form->createView()
        ]);
    }
}
