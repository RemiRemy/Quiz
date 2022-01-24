<?php

namespace App\Email;

use App\Entity\UserPasswordForgot;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class PasswordForgotEmail extends TemplatedEmail {
    public function __construct(UserPasswordForgot $passwordForgot, Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct( $headers, $body );

        $this->subject("Changement de mot de passe")
            ->htmlTemplate("emails/password_forgot.html.twig")
            ->textTemplate("emails/password_forgot.txt.twig")
            ->context(["token" => $passwordForgot->getToken()])
        ;
    }
}