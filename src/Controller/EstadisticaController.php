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
        String $tipo

    ): Response
    {
        $subtitulo='';
        
switch ($tipo) {
    case 'unafecha':
        $subtitulo='En una fecha dada';
        break;
    case 'hastafecha':
        $subtitulo='Hasta una fecha dada';
        break;
    case 'entrefechas':
        $subtitulo='Entre dos Fechas';
        break;
}

       

        $clase_form="block";
        $clase_info="none";
        $em = $doctrine->getManager();
        $infoVacunatorios = $em->getRepository(Vacunatorios::class)->findAll();
        $vacunatorios = [$infoVacunatorios[0]->getNombre(),$infoVacunatorios[1]->getNombre(),$infoVacunatorios[2]->getNombre()];

        $vacunas =["Gripe", "Covid-19", "Fiebre Amarilla"];

        $turnosAMostrar = array();


        if (count($request->request->All()) > 0){
            $data = $request->request->All();
            $clase_form="none";
            $clase_info="block";
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
                'fecha1' => $data['fecha1']
            );
            if ($tipo == 'entrefechas'){
                $dataForm['fecha2']= $data['fecha2'];
            }

            $strEstados= 'x';
            if ($dataForm['estados']['estado1']) $strEstados .= 'ASIGNADO';
            if ($dataForm['estados']['estado2']) $strEstados .= 'CANCELADO';
            if ($dataForm['estados']['estado3']) $strEstados .= 'APLICADA';


            // dd($tipo, $dataForm);

            switch ($tipo) {
                case 'unafecha':
                    $turnosDB =  $em->getRepository(Turnos::class)->findTurnosByDate($dataForm['fecha1']);
                    break;
                case 'hastafecha':
                    $turnosDB =  $em->getRepository(Turnos::class)->findTurnosUntilDate($dataForm['fecha1']);
                    break;
                case 'entrefechas':
                    $turnosDB =  $em->getRepository(Turnos::class)->findTurnosBetweenDates($dataForm['fecha1'],$dataForm['fecha2']);
                    break;
            }
            foreach($turnosDB as $turno){

                if ( ( $dataForm['vacunatorios']['vacunatorio' . $turno->getVacunatorioId()]) &&
                ( $dataForm['vacunas']['vacuna' . $turno->getVacunaId()]) &&
                (strpos($strEstados, $turno->getEstado() ))
                    
                ){

                    $arregloTurno = array(
                        'id' => $turno->getId(),
                        'fecha' => date_format($turno->getFecha(), "d-m-Y"),
                        'vacunatorio' => $vacunatorios[$turno->getVacunatorioId() -1],
                        'vacuna' => $vacunas[$turno->getVacunaId() -1],
                        'estado' => $turno->getEstado(),
                        'clase_form' => $clase_form,
                        'clase_info' => $clase_info,
                    );
                    
                    array_push($turnosAMostrar, $arregloTurno);
                }
                

            }


            // dd($dataForm,$turnosDB, $turnosAMostrar, $strEstados);

            // dd($turnosAMostrar);






        }
 
        $hoy =  date_format(new DateTime(), "Y-m-d");


      

        return $this->render('estadistica/index.html.twig', [
            'controller_name' => 'EstadisticaController',
            'fecha_hoy' => $hoy,
            'vacunatorios'=> $vacunatorios,
            'turnos' => $turnosAMostrar,
            'clase_form' => $clase_form,
            'clase_info' => $clase_info,
            'subtitulo' => $subtitulo,
            'tipo' => $tipo,
            // 'data' => $data,
            // 'form' => $form->createView(),

        ]);
    }
}
