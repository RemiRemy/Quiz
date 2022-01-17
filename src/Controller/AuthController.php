<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPasswordForgot;
use App\Form\PasswordChangeType;
use App\Form\PasswordForgotType;
use App\Form\RegistrationType;
use App\Repository\UserPasswordForgotRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\ByteString;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $entityManager = $this->getDoctrine()->getManager();
            // On met à jour le mot de passe de l'utilisateur avec le hash
            $user->setPassword( $passwordHasher->hashPassword( $user, $form->get( "password" )->getData() ) );
            $entityManager->persist( $user );
            $entityManager->flush();

            $this->addFlash( "success", "Bienvenue parmi nos membres <strong>{$user->getPseudo()}</strong> !" );
            return $this->redirectToRoute( "app_login" );
        }

        return $this->renderForm( 'auth/register.html.twig', ['form' => $form]);
    }

    #[Route('/password/forgot', name: 'app_password_forgot', methods: ['GET', 'POST'])]
    public function passwordForgot(Request $request, UserRepository $repository, MailerInterface $mailer): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(PasswordForgotType::class);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $user = $repository->findByEmail($form->get("email")->getData());

            if ($user) {
                // On récupère UserPasswordForgot de l'utilisateur s'il est null on crée une nouvelle instance.
                $passwordForgot = $user->getPasswordForgot() ?? new UserPasswordForgot();
                $passwordForgot->setToken(ByteString::fromRandom())->setCreatedAt(new DateTimeImmutable()); // On génère un string random et on le met à la date du jour

                // On sauvegarde en base de donné le UserPasswordForgot
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist( $passwordForgot );
                $entityManager->flush();

                // On met à jour l'utilisateur
                $user->setPasswordForgot($passwordForgot);

                // TODO revoir ca
                // Envoi de l'email
                $email = (new Email())
                    ->from('no-reply@quizzup.fr')
                    ->to($user->getEmail())
                    ->subject('Changement de mot de passe')
                    ->text('Pour changer votre mot de passe cliquez sur le lien ' . $this->generateUrl('app_password_change', ['token' => $passwordForgot->getToken()], UrlGeneratorInterface::NETWORK_PATH))
                    ->html('Pour changer votre mot de passe cliquez sur le lien <a href="' . $this->generateUrl('app_password_change', ['token' => $passwordForgot->getToken()], UrlGeneratorInterface::NETWORK_PATH) . '">Changer le mot de passe</a>')
                ;
                $mailer->send($email);
            }

            $this->addFlash( "success", "Vous allez recevoir un email a l'adresse <strong>{$form->get("email")->getData()}</strong>, une fois la vérification effectuée vous allez pouvoir changer de mot de passe." );
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm( 'auth/password/forgot.html.twig', ['form' => $form]);
    }

    #[Route('/password/change', name: 'app_password_change', methods: ['GET', 'POST'])]
    public function passwordChange(Request $request, UserPasswordForgotRepository $repository, UserPasswordHasherInterface $passwordHasher): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $passwordToken = $repository->findByToken($request->get("token"));
        if ( !$passwordToken || $this->isExpired($passwordToken)) {
            // On supprime le token périmé
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove( $passwordToken );
            $entityManager->flush();

            $this->addFlash("error", "Action impossible. Le jeton d'accès est introuvable ou expiré.");
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(PasswordChangeType::class, ["token" => $passwordToken->getToken()]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $user = $passwordToken->getUser();

            // On supprime le token
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove( $passwordToken );
            $entityManager->flush();

            // On met à jour le mot de passe de l'utilisateur avec le hash
            $user->setPassword( $passwordHasher->hashPassword( $user, $form->get( "password" )->getData() ) );
            $entityManager->flush();

            $this->addFlash( "success", "Votre mot de passe a été changé. Vous pouvez désormais vous connecter." );
            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm( 'auth/password/change.html.twig', ['form' => $form]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    private function isExpired(UserPasswordForgot $passwordForgot): bool
    {
        $expirationDate = new DateTimeImmutable('-1 day');
        return $passwordForgot->getCreatedAt() <= $expirationDate;
    }
}
