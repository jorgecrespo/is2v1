<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\LoginPacienteType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomLoginPacienteController extends AbstractController
{
    #[Route('/custom/login/paciente', name: 'app_custom_login_paciente')]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        CustomService $cs
    ): Response {

        $paciente = new Pacientes();
        $form = $this->createForm(LoginPacienteType::class, $paciente);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // if ($form->isSubmitted() && $form->isValid()){
            $mailForm = $form['mail']->getData();
            $tokenForm = $form['token']->getData();
            $passForm = $form['pass']->getData();

            $em = $doctrine->getManager();

            $pacienteDB = new Pacientes();

            $dataForm = "mailForm: $mailForm, tokenForm:  $tokenForm, passForm: $passForm";
            $pacienteDB = $em->getRepository(Pacientes::class)->findOneByEmail($mailForm);
            // dd($pacienteDB);
            $mensaje = ($pacienteDB != null) ? 'Paciente mail: ' . $pacienteDB->getMail() . ', Token: ' . $pacienteDB->getToken() . ", Pass: " .  $pacienteDB->getPass() : 'Paciente no encontrado';
            $mensajeFinal = $dataForm . $mensaje;

            // dd('hola!');
            if (
                ($mailForm == $pacienteDB->getMail()) &&
                ($tokenForm == $pacienteDB->getToken()) &&
                ($passForm == base64_decode($pacienteDB->getPass()))
            ) {
                $mensajeFinal = "Credenciales correctas";
                $cs->setUser($mailForm, 'P');
                return $this->redirectToRoute(route: 'app_homepaciente');
            } else {
                $mensajeFinal .= "Credenciales incorrectas";
            }

            $this->addFlash(type: 'success', message: $mensajeFinal);
        }


        return $this->render('custom_login_paciente/index.html.twig', [
            'controller_name' => 'CustomLoginPacienteController',
            'formulario'      => $form->createView()

        ]);
    }
}
