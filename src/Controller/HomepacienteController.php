<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Service\CustomService;
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

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }
        

        $mail = $cs->getUser()['user'];
        $em = $doctrine->getManager();
        $paciente = $em->getRepository(Pacientes::class)->findOneByMail($mail);
        // dd($paciente);
        return $this->render('homepaciente/index.html.twig', [
            'controller_name' => 'HomepacienteController',
            'paciente' => $paciente
        ]);
    }
}
