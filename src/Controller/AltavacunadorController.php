<?php

namespace App\Controller;

use App\Form\VacunadorType;
use App\Entity\Usuarios;
use App\Service\CustomService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AltavacunadorController extends AbstractController
{
    #[Route('/altavacunador', name: 'app_altavacunador')]
    public function index(
        Request $request,
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response {

        $valido = true;

        if (!$cs->validarUrl($request->getPathinfo())) {
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

        $usuario = new Usuarios();
        $form = $this->createForm(VacunadorType::class, $usuario);
        try {

            $form->handleRequest($request);
        } catch (InvalidArgumentException) {
            if ($form['mail']->getData() == null) {
                $this->addFlash(type: 'error', message: 'Debe ingresar una direccion de mail');
                $valido = false;
            }

            if ($form['pass']->getData() == null) {
                $this->addFlash(type: 'error', message: 'Debe ingresar una contraseña');
                $valido = false;
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {

            if (!filter_var($form['mail']->getData(), FILTER_VALIDATE_EMAIL)) {
                $this->addFlash(type: 'error', message: 'El mail no tiene un formato válido');
                $valido = false;
            }

            if (strlen($form['pass']->getData()) < 6) {
                $this->addFlash(type: 'error', message: 'La contraseña debe tener al menos 6 caracteres');
                $valido = false;
            } else {
                $usuario->setPass($form['pass']->getData());
            }

            if ($form['nombre']->getData() == null || strlen($form['nombre']->getData()) == 0) {
                $this->addFlash(type: 'error', message: 'Debe ingresar su nombre y apellido.');
                $valido = false;
            }

            if (preg_match("/[0-9]/", $form['nombre']->getData())) {
                $this->addFlash(type: 'error', message: 'Nombre y apellido no pueden contener números.');
                $valido = false;
            }

            if ($form['dni']->getData() == null) {
                $usuario->setDni(0);
            } else if (!preg_match("/^([0-9])*$/", $form['dni']->getData())){
                $this->addFlash(type: 'error', message: 'El DNI solo admite números.');
                $valido = false; 
            }

            if ($form['telefono']->getData() == null) {
                $usuario->setTelefono('');
            } else if (!preg_match("/^([0-9])*$/", $form['telefono']->getData())){
                $this->addFlash(type: 'error', message: 'El teléfono solo admite números.');
                $valido = false; 
            }




            $em = $doctrine->getManager();
            $usuario->setEsAdmin(0);
            // $hashedPassword = $passwordHasher->hashPassword(
            //     $usuario,
            //     $form['pass']->getData()
            // );
            $usuario->setPass($form['pass']->getData());

            $em->persist($usuario);
            if ($valido) {
                try {
                    $em->flush();
                } catch (UniqueConstraintViolationException) {
                    $this->addFlash(type: 'error', message: 'El mail ya está registrado.');
                    $valido = false;
                }
            }
            if ($valido) {

                $this->addFlash(type: 'success', message: 'Vacunador dado de alta exitosamente.');
                // return $this->redirectToRoute( route : 'app_homepage');
                return $this->redirect($cs->getHomePageByUser());
            }
        }



        return $this->render('altavacunador/index.html.twig', [
            'formulario'      => $form->createView()
        ]);
    }
}
