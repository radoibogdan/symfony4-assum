<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'help' => 'Le mot de passe doit être composé de 12 caractères dont un minimum : 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial'
                ],
                'second_options' => ['label' => 'Confirmation mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
//                    new Length([
//                        'min' => 8,
//                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
//                        'max' => 4096
//                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ÿ0-9]).{12,}$/',
                        'message' => 'Le mot de passe doit être composé de 12 caractères dont un minimum : 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial (dans un ordre aléatoire).'
                    ])
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
