<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Else_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacunadoresporcentroController extends AbstractController
{
    #[Route('/vacunadoresporcentro', name: 'app_vacunadoresporcentro')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $em = $doctrine->getManager();


        
        $defaultData = ['V1' => true, 'V2' => true, 'V3' => true];

        //  dd($request->request->get('form'));
        $data = isSet($request->request->all()["form"]["baja"])?$request->request->all()["form"]["baja"]: null;
        if ($data){
            // dd($data);
            $em->getRepository(Usuarios::class)->bajaUsuario($data);
           
            

        }
        // dd($request);


        $data= [
        "rol_req" => "A",
        "operacion" => "",
        "vacunador" => "",
        "vacunadores"=> [
            "vacunadores1" => [],
            "vacunadores2" => [],
            "vacunadores3" => [],
        ]];

        if ($data["operacion"] != ""){
            dd('operacion: '. $data["operacino"]);
        }


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
            ->add('baja', HiddenType::class, [
                'required' => false,
            ])
            ->add('mod', HiddenType::class, [
                'required' => false,
            ])
            ->add('Actualizar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $checks = $form->getData();
            if ( $checks["V1"] == true)
            $data["vacunadores"]["vacunadores1"] = $em->getRepository(Usuarios::class)->findByVacunatorio(1);
            if ( $checks["V2"] == true)
            $data["vacunadores"]["vacunadores2"] = $em->getRepository(Usuarios::class)->findByVacunatorio(2);
            if ( $checks["V3"] == true)
            $data["vacunadores"]["vacunadores3"] = $em->getRepository(Usuarios::class)->findByVacunatorio(3);
        } else {
            $checks=$defaultData;

            $data["vacunadores"]["vacunadores1"] = $em->getRepository(Usuarios::class)->findByVacunatorio(1);
            $data["vacunadores"]["vacunadores2"] = $em->getRepository(Usuarios::class)->findByVacunatorio(2);
            $data["vacunadores"]["vacunadores3"] = $em->getRepository(Usuarios::class)->findByVacunatorio(3);
            
        }

        // dd($data);
        return $this->render('vacunadoresporcentro/index.html.twig', [
            'controller_name' => 'Vacunadores por centroController',
            'vacunadores1' => $data["vacunadores"]["vacunadores1"],
            'vacunadores2' => $data["vacunadores"]["vacunadores2"],
            'vacunadores3' => $data["vacunadores"]["vacunadores3"],
            'checks'       => $checks,
            'form' => $form->createView(),
        ]);
    }
}

