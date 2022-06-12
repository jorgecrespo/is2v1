<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailSenderController extends AbstractController
{
    #[Route('/mail/sender', name: 'app_mail_sender')]
    public function index(
        MailerInterface $mailer,
    ): Response
    {


        $email = (new Email())
        ->from('info@vacunassist.com.ar')
        ->to('jorgeluiscrespo@gmail.com')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('llego el mail!')
        ->text('Texto de prueba para el envio de mails')
        ->html('<p>texto en un p html</p>');

    $mailer->send($email);
        // dd($mailer);


        return $this->render('mail_sender/index.html.twig', [
            'controller_name' => 'MailSenderController',
        ]);
    }
} 
