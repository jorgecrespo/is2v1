<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\LoginPacienteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CustomLoginPacienteController extends AbstractController
{
    #[Route('/custom/login/paciente', name: 'app_custom_login_paciente')]
    public function index(
        Request $request, 
        ManagerRegistry $doctrine, 
        // UserPasswordHasherInterface $passwordHasher
        ): Response
    {

        $paciente = new Pacientes();
        $form = $this->createForm(LoginPacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted() ){
            // if ($form->isSubmitted() && $form->isValid()){
            $mailForm = $form['mail']->getData();
            $tokenForm = $form['token']->getData();
            $passForm = $form['pass']->getData();

            $em = $doctrine->getManager();

            // $pacienteDB = new Pacientes();

            // $hashedPassword = $passwordHasher->hashPassword(
            //     $paciente,
            //     $passForm
            // );
            
            $dataForm = "mailForm: $mailForm, tokenForm:  $tokenForm, passForm: $passForm";
            $pacienteDB = $em->getRepository(Pacientes::class)->findOneByEmail($mailForm);

            $mensaje = ($pacienteDB != null) ? 'Paciente mail: '. $pacienteDB->getMail() . ', Token: '. $pacienteDB->getToken() . ", Pass: " .  $pacienteDB->getPass(): 'Paciente no encontrado';
            $mensajeFinal = $dataForm . $mensaje;
            
            if (
                ($mailForm == $pacienteDB->getMail()) &&
                ($tokenForm == $pacienteDB->getToken()) &&
                ($passForm == $pacienteDB->getPass())
            ) {
                $mensajeFinal = "Credenciales correctas";
            } else {
                $mensajeFinal .= "Credenciales incorrectas";
            }



            
            // TODO: encriptar contraseña igual y compararlas

            $this->addFlash(type: 'success', message: $mensajeFinal  );

            return $this->redirectToRoute( route : 'app_custom_login_paciente');



        }


        return $this->render('custom_login_paciente/index.html.twig', [
            'controller_name' => 'CustomLoginPacienteController',
            'formulario'      => $form->createView()

        ]);
    }
}
