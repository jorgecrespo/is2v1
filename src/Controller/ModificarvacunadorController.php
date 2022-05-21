<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\ModvacunadorType;
use App\Service\CustomService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModificarvacunadorController extends AbstractController
{
    #[Route('/modificarvacunador', name: 'app_modificarvacunador')]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        CustomService $cs,
    ): Response {

        $valido = true;

        if (!$cs->validarUrl($request->getPathinfo())) {
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }


        $em = $doctrine->getManager();
        // dd( $request);

        $id = isset($request->request->all()["form"]["mod"]) ? $request->request->all()["form"]["mod"] : null;
        if (!$id) {
            // TODO: ver que va aca aca
        }

        $usuarioDB = $em->getRepository(Usuarios::class)->findOneById($id);
        $form = $this->createForm(ModvacunadorType::class, $usuarioDB);

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

            $id = $request->request->all()['modvacunador']['id'];
            $usuarioDB = $em->getRepository(Usuarios::class)->findOneById($id);

            if (!filter_var($form['mail']->getData(), FILTER_VALIDATE_EMAIL)) {
                $this->addFlash(type: 'error', message: 'El mail no tiene un formato válido');
                $valido = false;
            } else {
                $usuarioDB->setMail($form->getData()->getMail());
            }

            if (strlen($form['pass']->getData()) < 6) {
                $this->addFlash(type: 'error', message: 'La contraseña debe tener al menos 6 caracteres');
                $valido = false;
            } else {
                $usuarioDB->setPass($form['pass']->getData());
            }

            if ($form['nombre']->getData() == null || strlen($form['nombre']->getData()) == 0) {
                $this->addFlash(type: 'error', message: 'Debe ingresar su nombre y apellido.');
                $valido = false;
            }

            if (preg_match("/[0-9]/", $form['nombre']->getData())) {
                $this->addFlash(type: 'error', message: 'Nombre y apellido no pueden contener números.');
                $valido = false;
            } else {
                $usuarioDB->setNombre($form->getData()->getNombre());
            }

            if ($form['dni']->getData() == null) {
                $usuarioDB->setDni(0);
            } else if (!preg_match("/^([0-9])*$/", $form['dni']->getData())) {
                $this->addFlash(type: 'error', message: 'El DNI solo admite números.');
                $valido = false;
            } else {
                $usuarioDB->setDni($form['dni']->getData());

            }

            if ($form['telefono']->getData() == null) {
                $usuarioDB->setTelefono('');
            } else if (!preg_match("/^([0-9])*$/", $form['telefono']->getData())) {
                $this->addFlash(type: 'error', message: 'El teléfono solo admite números.');
                $valido = false;
            } else {
                $usuarioDB->setTelefono($form['telefono']->getData());
            }

            $usuarioDB->setVacunatorioId($form->getData()->getVacunatorioId());

            if ($valido) {
                try {
                    $em->flush();
                    $this->addFlash(type: 'success', message: 'Vacunador modificado exitosamente.');
                    return $this->redirectToRoute(route: 'app_vacunadoresporcentro');
                } catch (UniqueConstraintViolationException) {
                    $this->addFlash(type: 'error', message: 'El mail ingresado ya está registrado.');
                    $valido = false;
                }


            }
        }

        return $this->render('modificarvacunador/index.html.twig', [
            'formulario'      => $form->createView(),
        ]);
    }
}
