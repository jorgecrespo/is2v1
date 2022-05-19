<?php

namespace App\Controller;

use App\Form\VacunadorType;
use App\Entity\Usuarios;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AltavacunadorController extends AbstractController
{
    #[Route('/altavacunador', name: 'app_altavacunador')]
    public function index(
        Request $request, 
        CustomService $cs,
        ManagerRegistry $doctrine, 
        ): Response
    {

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }

        $usuario = new Usuarios();
        $form = $this->createForm(VacunadorType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $usuario->setEsAdmin(0);
            // $hashedPassword = $passwordHasher->hashPassword(
            //     $usuario,
            //     $form['pass']->getData()
            // );
            $usuario->setPass($form['pass']->getData());
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(type: 'success', message:'Vacunador dado de alta exitosamente.');
            return $this->redirectToRoute( route : 'app_homepage');
        }



        return $this->render('altavacunador/index.html.twig', [
            'formulario'      => $form->createView()
        ]);
    }
}
