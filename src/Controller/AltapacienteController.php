<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacienteType;
use App\Service\CustomService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
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
        $valido = true;

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

        $paciente = new Pacientes();
        $form = $this->createForm(PacienteType::class, $paciente);
        try{

            $form->handleRequest($request);
        }catch ( InvalidArgumentException) {
            if ($form['fecha_nac']->getData() == null){
                $this->addFlash(type: 'error', message: 'Debe ingresar la fecha de nacimiento');
                        $valido = false;
            }

        }
        if ($form->isSubmitted() && $form->isValid()){

            if (!filter_var($form['mail']->getData(), FILTER_VALIDATE_EMAIL)) {
                $this->addFlash(type: 'error', message: 'El mail no tiene un formato válido');
                $valido = false;
            }

            if (strlen($form['pass']->getData()) < 6){
                $this->addFlash(type: 'error', message: 'La contraseña debe tener al menos 6 caracteres');
                $valido = false;
            } else {
                $paciente->setPass($form['pass']->getData());
            }

            if( strlen($form['nombre']->getData()) == 0 || strlen($form['apellido']->getData()) == 0){
                $this->addFlash(type: 'error', message: 'Debe ingresar su nombre y apellido.');
                $valido = false;

            }

            if (preg_match("/[0-9]/", $form['nombre']->getData()) || preg_match("/[0-9]/", $form['apellido']->getData())){
                $this->addFlash(type: 'error', message: 'El nombre y el apellido no pueden contener números.');
                $valido = false;
                // dd('nombre con numero');
            } else {
                $token = $cs->generarToken($form['nombre']->getData());
                $paciente->setToken($token);
                // dd('nombre sin numero');
            }



            $em = $doctrine->getManager();
            // dd($form['nombre']->getData(), $token);

            
            $paciente->setNotificacionPendiente(false);
            $em->persist($paciente);




            if ($valido){

                try {
                    $em->flush();
                } catch (UniqueConstraintViolationException) {
                    $this->addFlash(type: 'error', message: 'El mail ingresado ya está registrado.');
                    $valido = false;
                }
            }
            if ($valido){
            $cs->setUser($form['mail']->getData(), 'P');
    

            $this->addFlash(type: 'success', message:'Paciente dado de alta exitosamente. Mail: ' . $cs->getUser()['user']
            . ' Rol: '.$cs->getUser()['rol']);
            return $this->redirectToRoute( route : 'app_token');
            }
        }


        return $this->render('altapaciente/index.html.twig', [
            'formulario'      => $form->createView()

        ]);
    }
}
