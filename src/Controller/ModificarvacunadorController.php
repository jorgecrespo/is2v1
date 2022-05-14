<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\VacunadorType;
use Doctrine\Persistence\ManagerRegistry;
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
    ): Response {
        $em = $doctrine->getManager();
        // dd( $request);

        $data = isset($request->request->all()["form"]["mod"]) ? $request->request->all()["form"]["mod"] : null;
        if (!$data) {
            //TODO: salir de aca
            // dd("salir de aca!!");
        }


        $usuarioDB = $em->getRepository(Usuarios::class)->findOneByMail($data);
            // dd($usuarioDB);
        $form = $this->createForm(VacunadorType::class, $usuarioDB);
     
       
 
        // dd($form->get('mail'));
        // dd($form);
        // dd($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request, $form);
            $data = $request->request->all()['vacunador']['mail'];
            // dd($data);
            $usuarioDB = $em->getRepository(Usuarios::class)->findOneByMail($data);
            $usuarioDB->setNombre($form->getData()->getNombre());
            // $usuarioDB->setMail($form->getData()->getMail());
            $usuarioDB->setPass($form->getData()->getPass());
            $usuarioDB->setVacunatorioId($form->getData()->getVacunatorioId());
            $em->flush();
            $this->addFlash(type: 'success', message: 'Vacunador modificado exitosamente.');
            return $this->redirectToRoute(route: 'app_vacunadoresporcentro');
        }




        return $this->render('modificarvacunador/index.html.twig', [
            'controller_name' => 'ModificarvacunadorController',
            'formulario'      => $form->createView(),
        ]);
    }
}
