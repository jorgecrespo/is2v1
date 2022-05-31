<?php

namespace App\Controller;

use App\Entity\Vacunatorios;
use App\Form\ModVacunatorioType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModificarVacunatorioController extends AbstractController
{
    #[Route('/modificar/vacunatorio', name: 'app_modificar_vacunatorio')]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        CustomService $cs,
    ){
        $em = $doctrine->getManager();

        // dd($request->request->all()['data1']);
         if (isset($request->request->all()['data1']))
        { 
            $id = $request->request->all()['data1'];
            $vacunatorioDB = $em->getRepository(Vacunatorios::class)->findOneById($id);
        } else {
            // dd($request->request->all()['mod_vacunatorio']['id']);
            $id = $request->request->all()['mod_vacunatorio']['id'];
            $vacunatorioDB = $em->getRepository(Vacunatorios::class)->findOneById($id);

        } 
        $form = $this->createForm(ModVacunatorioType::class, $vacunatorioDB);
        // dd($request);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            // dd($form->getData());
            $id = $request->request->all()['mod_vacunatorio']['id'];
            $em = $doctrine->getManager();

            $VacunatorioDB = $em->getRepository(Vacunatorios::class)->findOneById($id);
            $vacunatorioDB->setNombre($form->getData()->getNombre());
            $vacunatorioDB->setDireccion($form->getData()->getDireccion());
            $em->flush();
            $this->addFlash(type: 'success', message: 'Vacunatorio modificado exitosamente.');
            return $this->redirectToRoute(route: 'app_homeadmin');

        }



        return $this->render('modificar_vacunatorio/index.html.twig', [
            'formulario'      => $form->createView(),
        ]);
    }
}
