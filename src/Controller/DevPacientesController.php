<?php

namespace App\Controller;

use App\Entity\Pacientes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevPacientesController extends AbstractController
{
    #[Route('/dev/pacientes', name: 'app_dev_pacientes')]
    public function index(
        ManagerRegistry $doctrine,
    ): Response
    {
        $em = $doctrine->getManager();
        $pacientes = $em->getRepository(Pacientes::class)->findAll();
        // dd($pacientes[0]);
        foreach($pacientes as &$paciente){
            $paciente->setPass(base64_decode($paciente->getPass()));
        }



        return $this->render('dev_pacientes/index.html.twig', [
            'controller_name' => 'DevPacientesController',
            'pacientes' => $pacientes
        ]);
    }
}
