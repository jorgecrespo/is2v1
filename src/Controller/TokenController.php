<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    #[Route('/token', name: 'app_token')]
    public function index(
        CustomService $cs,
        ManagerRegistry $doctrine,
    ): Response
    {

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
