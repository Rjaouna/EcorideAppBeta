<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;                 // <-- Assure-toi d’avoir cette entité
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private const WELCOME_CREDITS = 20; // si tu veux 20 crédits offerts

    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER', 'ROLE_PASSAGER']);
            $user->setFirstConnexion(false);

            // 2) Password hash
            $plainPassword = (string) $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // 3) Créer le wallet s’il n’existe pas
            if (null === $user->getWallet()) {
                $wallet = new Wallet();
                $wallet->setBalance(self::WELCOME_CREDITS); // 20 crédits offerts
                $wallet->setOwner($user);
                $em->persist($wallet);
            }

            $em->persist($user);
            $em->flush();

            // 4) Envoi email de confirmation
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@ecoride.com', 'EcoRide'))
                    ->to((string) $user->getEmail())
                    ->subject('Please confirm your email address')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // 5) Redirection
            // Option : rediriger vers une page "check your mailbox" ou vers login
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            if (!$user instanceof User) {
                $this->addFlash('verify_email_error', 'Veuillez vous connecter puis cliquer à nouveau sur le lien de vérification.');
                return $this->redirectToRoute('app_login');
            }

            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a bien été vérifiée.');
        return $this->redirectToRoute('app_home');
    }
}
