<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacienteType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AltapacienteController extends AbstractController
{
    #[Route('/altapaciente', name: 'app_altapaciente')]
    public function index(
    Request $request, 
    ManagerRegistry $doctrine, 
    CustomService $cs,

    ): Response
    {

        $paciente = new Pacientes();
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $paciente->setToken(substr($form['nombre']->getData(), 0, 5));

            $hashedPassword = base64_encode($form['pass']->getData());
            $paciente->setPass($hashedPassword);
            $paciente->setNotificacionPendiente(false);
            $em->persist($paciente);
            $em->flush();
    

            $this->addFlash(type: 'success', message:'Paciente dado de alta exitosamente.' . $cs->getUser()['user']
            . ' Rol: '.$cs->getUser()['user']);
            return $this->redirectToRoute( route : 'app_token');
        }


        return $this->render('altapaciente/index.html.twig', [
            'controller_name' => 'AltapacienteController',
            'formulario'      => $form->createView()

        ]);
    }
}
