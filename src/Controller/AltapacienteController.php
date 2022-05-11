<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacienteType;
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
    // UserPasswordHasherInterface $passwordHasher,
    RequestStack $requestStack,
    ): Response
    {

        $paciente = new Pacientes();
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $paciente->setToken($form['nombre']->getData());
            // $hashedPassword = $passwordHasher->hashPassword(
            //     $paciente,
            //     $form['pass']->getData()
            // );
            $hashedPassword = base64_encode($form['pass']->getData());
            $paciente->setPass($hashedPassword);
            $paciente->setNotificacionPendiente(false);
            $em->persist($paciente);
            $em->flush();
            // $_SESSION["id"] = $form['mail']->getData();
            // $_SESSION["rol"] = "P";
            $session = $requestStack->getSession();
            $session->set('user',$form['mail']->getData() );
            $session->set('rol','P');

            $this->addFlash(type: 'success', message:'Paciente dado de alta exitosamente.' . $session->get('user') . $session->get('rol'));
            return $this->redirectToRoute( route : 'app_homepage');
        }


        return $this->render('altapaciente/index.html.twig', [
            'controller_name' => 'AltapacienteController',
            'formulario'      => $form->createView()

        ]);
    }
}
