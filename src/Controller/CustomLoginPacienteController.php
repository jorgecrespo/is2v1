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

        if (!$cs->validarUrl($request->getPathinfo())) {
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

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

            $pacienteDB = $em->getRepository(Pacientes::class)->findOneByEmail($mailForm);

            if ($pacienteDB != null && ($pacienteDB->getMail() == $mailForm)) {

                if ($passForm == $pacienteDB->getPass()) {

                    if (strtoupper($tokenForm) == $pacienteDB->getToken()) {
                        $cs->setUser($mailForm, 'P');
                        return $this->redirectToRoute(route: 'app_homepaciente');
                    } else {
                        $mensajeFinal = "Token incorrecto. Vuelva a intentar.";
                        $this->addFlash(type: 'error', message: $mensajeFinal);
                    }
                } else {
                    $mensajeFinal = "Contraseña incorrecta. Vuelva a intentar.";
                    $this->addFlash(type: 'error', message: $mensajeFinal);
                }
            } else {
                // dd($pacienteDB,$pacienteDB->getMail(),$mailForm );
                $mensajeFinal = "Mail no válido. Vuelva a intentar.";
                $this->addFlash(type: 'error', message: $mensajeFinal);
            }
        }


        return $this->render('custom_login_paciente/index.html.twig', [
            'formulario'      => $form->createView()

        ]);
    }
}
