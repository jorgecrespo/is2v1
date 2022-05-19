<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\ModvacunadorType;
use App\Service\CustomService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModificarvacunadorController extends AbstractController
{
    #[Route('/modificarvacunador', name: 'app_modificarvacunador')]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        CustomService $cs,
    ): Response {


        if (!$cs->validarUrl($request->getPathinfo())){
            $this->addFlash(type: 'error', message: 'Página no válida. Ha sido redireccionado a su página principal');
            return $this->redirect($cs->getHomePageByUser());
        }


        $em = $doctrine->getManager();
        // dd( $request);

        $id = isset($request->request->all()["form"]["mod"]) ? $request->request->all()["form"]["mod"] : null;
        if (!$id) {
            
        }

        $usuarioDB = $em->getRepository(Usuarios::class)->findOneById($id);
        $form = $this->createForm(ModvacunadorType::class, $usuarioDB);
     
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //  dd($request->request->all());
            $id = $request->request->all()['modvacunador']['id'];
            // dd($id);
            $usuarioDB = $em->getRepository(Usuarios::class)->findOneById($id);
            $usuarioDB->setNombre($form->getData()->getNombre());
            $usuarioDB->setMail($form->getData()->getMail());
            // dd($form->getData()->getPass());
            $usuarioDB->setPass($form->getData()->getPass());
            $usuarioDB->setVacunatorioId($form->getData()->getVacunatorioId());
            $em->flush();
            $this->addFlash(type: 'success', message: 'Vacunador modificado exitosamente.');
            return $this->redirectToRoute(route: 'app_vacunadoresporcentro');
        }

        return $this->render('modificarvacunador/index.html.twig', [
            'formulario'      => $form->createView(),
        ]);
    }
}
