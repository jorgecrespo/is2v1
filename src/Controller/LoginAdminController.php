<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\LoginVacunadorType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginAdminController extends AbstractController
{
    #[Route('/login/admin', name: 'app_login_admin')]
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


        $usuario = new Usuarios();
        $form = $this->createForm(LoginVacunadorType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // if ($form->isSubmitted() && $form->isValid()){
            $mailForm = $form['mail']->getData();
            $passForm = $form['pass']->getData();

            $em = $doctrine->getManager();

            $usuarioDB = new Usuarios();

            $dataForm = "mailForm: $mailForm, passForm: $passForm  ";
            $usuarioDB = $em->getRepository(Usuarios::class)->findOneBy(['mail' => $mailForm]);
            $esAdmin = $usuarioDB->getEsAdmin();
            if ($esAdmin) {
                $rutaDestino = 'app_homeadmin';
                $userRol = 'A';
            } else {
                $rutaDestino = 'app_home_vacunador';
                $userRol = 'V';
            }

            $mensaje = ($usuarioDB != null) ? 'usuario mail: ' . $usuarioDB->getMail() . ", Pass: " . base64_decode( $usuarioDB->getPassword())  : 'usuario no encontrado';
            $mensajeFinal = $dataForm . $mensaje;

            if (
                ($mailForm == $usuarioDB->getMail()) &&
                // ( 
                ($passForm == $usuarioDB->getPassword())
                // || ($hashedPassword == $usuarioDB->getPass()) )
            ) {
                $mensajeFinal = "Credenciales correctas";
                $cs->setUser($mailForm, $userRol);
                return $this->redirectToRoute(route: $rutaDestino);
            } else {
                $mensajeFinal .= "Credenciales incorrectas";
            }

            $this->addFlash(type: 'success', message: $mensajeFinal);
        }


        return $this->render('login_admin/index.html.twig', [
            'controller_name' => 'Login de Vacunadores y Admin',
            'formulario'      => $form->createView()

        ]);
    }
}
