<?php

namespace App\Controller;

use App\Entity\Turnos;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstadisticaController extends AbstractController
{
    #[Route('/estadistica/{tipo}', name: 'app_estadistica')]
    public function index(
        String $tipo,
        ManagerRegistry $doctrine,

    ): Response
    {
        

        $em = $doctrine->getManager();
        $vacunatorios = $em->getRepository(Vacunatorios::class)->findAll();
        $vacunatoriosArr = [$vacunatorios[0]->getNombre(),$vacunatorios[1]->getNombre(),$vacunatorios[2]->getNombre()];
        // dd($vacunatoriosArr);

        $vacunas = $em->getRepository(Vacunas::class)->findAll();

        $turnos = $em->getRepository(Turnos::class)->findAll();

        $hoy =  date_format(new DateTime(), "Y-m-d");

        // dd($hoy);



        return $this->render('estadistica/index.html.twig', [
            'controller_name' => 'EstadisticaController',
            'vacunatorios' => $vacunatoriosArr,
            'fecha' => $hoy
        ]);
    }
}
