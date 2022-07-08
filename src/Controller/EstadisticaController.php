<?php

namespace App\Controller;

use App\Entity\Turnos;
use App\Entity\Usuarios;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstadisticaController extends AbstractController
{
    #[Route('/estadistica/{tipo}', name: 'app_estadistica')]
    public function index(
        ManagerRegistry $doctrine, 
        Request $request,
        CustomService $cs,

    ): Response
    {

        $em = $doctrine->getManager();
        $infoVacunatorios = $em->getRepository(Vacunatorios::class)->findAll();
        $vacunatorios = [$infoVacunatorios[0]->getNombre(),$infoVacunatorios[1]->getNombre(),$infoVacunatorios[2]->getNombre()];

        $vacunas =["Gripe", "Covid-19", "Fiebre Amarilla"];

        $turnosAMostrar = array();


        if (count($request->request->All()) > 0){
            $data = $request->request->All();
            // dd($data);
            $dataForm = array(
                'vacunatorios' => array(
                    'vacunatorio1' => isset($data['vacunatorio1']),
                    'vacunatorio2' => isset($data['vacunatorio2']),
                    'vacunatorio3' => isset($data['vacunatorio3']),
                ),
                'vacunas' => array(
                    'vacuna1' => isset($data['vakuna1']),
                    'vacuna2' => isset($data['vakuna2']),
                    'vacuna3' => isset($data['vakuna3']),
                ),
                'estados' => array(
                    'estado1' => isset($data['estado1']),
                    'estado2' => isset($data['estado2']),
                    'estado3' => isset($data['estado3']),
                ),
                'fecha' => $data['fecha1']
            );

            $strEstados= 'x';
            if ($dataForm['estados']['estado1']) $strEstados .= 'ASIGNADO';
            if ($dataForm['estados']['estado2']) $strEstados .= 'CANCELADO';
            if ($dataForm['estados']['estado3']) $strEstados .= 'APLICADA';



            $turnosDeFecha =  $em->getRepository(Turnos::class)->findTurnosByDate($dataForm['fecha']);
            foreach($turnosDeFecha as $turno){

                if ( ( $dataForm['vacunatorios']['vacunatorio' . $turno->getVacunatorioId()]) &&
                ( $dataForm['vacunas']['vacuna' . $turno->getVacunaId()]) &&
                (strpos($strEstados, $turno->getEstado() ))
                    
                ){

                    $arregloTurno = array(
                        'id' => $turno->getId(),
                        'fecha' => date_format($turno->getFecha(), "d-m-Y"),
                        'vacunatorio' => $vacunatorios[$turno->getVacunatorioId() -1],
                        'vacuna' => $vacunas[$turno->getVacunaId() -1],
                        'estado' => $turno->getEstado()
                    );
                    
                    array_push($turnosAMostrar, $arregloTurno);
                }
                

            }


            // dd($dataForm,$turnosDeFecha, $turnosAMostrar, $strEstados);

            // dd($turnosAMostrar);






        }
 
        $hoy =  date_format(new DateTime(), "Y-m-d");


      

        return $this->render('estadistica/index.html.twig', [
            'controller_name' => 'EstadisticaController',
            'fecha_hoy' => $hoy,
            'vacunatorios'=> $vacunatorios,
            'turnos' => $turnosAMostrar,
            // 'data' => $data,
            // 'form' => $form->createView(),

        ]);
    }
}
