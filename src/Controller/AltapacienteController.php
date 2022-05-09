<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacienteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AltapacienteController extends AbstractController
{
    #[Route('/altapaciente', name: 'app_altapaciente')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {

        $paciente = new Pacientes();
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $paciente->setToken($form['nombre']->getData());
            $hashedPassword = $passwordHasher->hashPassword(
                $paciente,
                $form['pass']->getData()
            );
            $paciente->setPass($hashedPassword);
            $paciente->setNotificacionPendiente(false);
            $em->persist($paciente);
            $em->flush();
            $this->addFlash(type: 'success', message:'Paciente dado de alta exitosamente.');
            return $this->redirectToRoute( route : 'app_homepage');
        }


        return $this->render('altapaciente/index.html.twig', [
            'controller_name' => 'AltapacienteController',
            'formulario'      => $form->createView()

        ]);
    }
}
