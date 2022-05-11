<?php
namespace App\Controller;

use App\Service\CustomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(CustomService $cs): Response
    {

        // $data = $session->get('rol') . $session->get('user');
        $data = 'Usuario: '. (string) $cs->getUser()['user'] . ' Rol: '. (string) $cs->getUser()['rol'];


        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'data' => $data,
        ]);
    }
}
