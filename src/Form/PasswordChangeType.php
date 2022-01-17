<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => "Mot de passe",
                    "constraints" => [
                        new NotBlank(['message' => "Veuillez entrer un mot de passe"]),
                        new Length([
                            'min'=> 6,
                            'minMessage' => "Votre mot de passe doit comporter au moins {{ limit }} caractères",
                            'max' => 4096]),
                    ]
                ],
                'second_options' => ['label' => "Confirmer le mot de passe"],
                'invalid_message' => "Les champs de mot de passe doivent correspondre"
            ])
            ->add('token', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
