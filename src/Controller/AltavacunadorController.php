<?php

namespace App\Controller;

use App\Form\VacunadorType;
use App\Entity\Usuarios;
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
        ManagerRegistry $doctrine, 
        ): Response
    {

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
            $hashedPassword = base64_encode($form['pass']->getData());
            $usuario->setPass($hashedPassword);
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(type: 'success', message:'Vacunador dado de alta exitosamente.');
            return $this->redirectToRoute( route : 'app_homepage');
        }



        return $this->render('altavacunador/index.html.twig', [
            'controller_name' => 'AltavacunadorController',
            'formulario'      => $form->createView()
        ]);
    }
}
