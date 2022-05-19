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

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

        $paciente = new Pacientes();
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $token = $cs->generarToken($form['nombre']->getData());
            // dd($form['nombre']->getData(), $token);
            $paciente->setToken($token);

            $paciente->setPass($form['pass']->getData());
            $paciente->setNotificacionPendiente(false);
            $em->persist($paciente);
            $em->flush();
            $cs->setUser($form['mail']->getData(), 'P');
    

            $this->addFlash(type: 'success', message:'Paciente dado de alta exitosamente. Mail' . $cs->getUser()['user']
            . ' Rol: '.$cs->getUser()['rol']);
            return $this->redirectToRoute( route : 'app_token');
        }


        return $this->render('altapaciente/index.html.twig', [
            'formulario'      => $form->createView()

        ]);
    }
}
