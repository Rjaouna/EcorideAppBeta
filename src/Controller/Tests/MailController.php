<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail_test')]
    public function mailTest(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('contact@getecoride.com')   // doit être autorisé par ton SMTP
            ->to('jaouna.ridouane@gmail.com') // mets bien ton adresse de test
            ->subject('Test mailer')
            ->text('Hello from Symfony Mailer');

        try {
            $mailer->send($email);
            return new Response('OK: mail envoyé');
        } catch (TransportExceptionInterface $e) {
            $msg = $e->getMessage();
            $prev = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            return new Response("Mailer ERROR: $msg\nPrev: $prev", 500);
        }
    }
}
