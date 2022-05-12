<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Else_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacunadoresporcentroController extends AbstractController
{
    #[Route('/vacunadoresporcentro', name: 'app_vacunadoresporcentro')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $defaultData = ['V1' => true, 'V2' => true, 'V3' => true];



        $vacunadores1=[];
        $vacunadores2=[];
        $vacunadores3=[];


        $form = $this->createFormBuilder($defaultData)
            ->add('V1', CheckboxType::class, [
                'label'    => 'Vacunatorio 1',
                'required' => false,
            ])
            ->add('V2', CheckboxType::class, [
                'label'    => 'Vacunatorio 2',
                'required' => false,
            ])
            ->add('V3', CheckboxType::class, [
                'label'    => 'Vacunatorio 3',
                'required' => false,
            ])
            ->add('Actualizar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if ($form->isSubmitted()) {
            
            $checks = $form->getData();
            if ( $checks["V1"] == true)
            $vacunadores1 = $em->getRepository(Usuarios::class)->findByVacunatorio(1);
            if ( $checks["V2"] == true)
            $vacunadores2 = $em->getRepository(Usuarios::class)->findByVacunatorio(2);
            if ( $checks["V3"] == true)
            $vacunadores3 = $em->getRepository(Usuarios::class)->findByVacunatorio(3);
        } else {
            $checks=$defaultData;

            $vacunadores3 = $em->getRepository(Usuarios::class)->findByVacunatorio(3);
            $vacunadores2 = $em->getRepository(Usuarios::class)->findByVacunatorio(2);
            $vacunadores1 = $em->getRepository(Usuarios::class)->findByVacunatorio(1);
            
        }


        return $this->render('vacunadoresporcentro/index.html.twig', [
            'controller_name' => 'VacunadoresporcentroController',
            'vacunadores1' => $vacunadores1,
            'vacunadores2' => $vacunadores2,
            'vacunadores3' => $vacunadores3,
            'checks'       => $checks,
            'form' => $form->createView(),
        ]);
    }
}
