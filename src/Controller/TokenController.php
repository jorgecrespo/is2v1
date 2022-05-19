<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    #[Route('/token', name: 'app_token')]
    public function index(
        CustomService $cs,
        Request $request, 
        ManagerRegistry $doctrine,
    ): Response
    {

        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }


        $em = $doctrine->getManager();
        $mail = $cs->getUser()['user'];
        $paciente = $em->getRepository(Pacientes::class)->findOneByMail($mail);
        $token = $paciente->getToken();


        return $this->render('token/index.html.twig', [
            'controller_name' => 'TokenController',
            'token'           => $token,
        ]);
    }
}
