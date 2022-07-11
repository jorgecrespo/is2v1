<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Service\CustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepacienteController extends AbstractController
{
    #[Route('/homepaciente', name: 'app_homepaciente')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
        Request $request, 
    ): Response
    {

        $notificaciones = $cs->notificacionesSinLeer();
        // dd($notificaciones);
        if (count($notificaciones) > 0){

        }

        // dd($request->headers->all()['referer'][0]);
        // "http://localhost:8000/custom/login/paciente"
        if (isset($request->headers->all()['referer'][0])){
            if ( substr($request->headers->all()['referer'][0], -22) == "/custom/login/paciente"){

            }
        }

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }        
        
        $mail = $cs->getUser()['user'];
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByMail($mail);

        $mailPaciente = $cs->getUser()['user'];
        $feNac = $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente)->getFechaNac();
        $feNacFormatted = date_format($feNac, "d-m-Y");
        $fechanac40diasAntes = new DateTime(  date("d-m-Y",strtotime($feNacFormatted ."- 40 days")));
        if ($cs->getEdad($fechanac40diasAntes)>=60){
            $edad = 60;
        } else {
            $edad = $cs->getEdad($feNac);
        }
        
        $diasPara60 = 
        
        $yaVacunado = (bool) $em->getRepository(Pacientes::class)->findOneByEmail($mailPaciente)->getVacunaHepatitisFecha() != null;
        // dd($yaVacunado);

        // si no hay problema, ir a /asignacion/turno/famarilla


        // dd($paciente);
        return $this->render('homepaciente/index.html.twig', [
            'paciente' => $paciente,
            'edad'=>$edad,
            'vacunado'=>$yaVacunado,
            'notificaciones' => $notificaciones,
        ]);
    }
}
