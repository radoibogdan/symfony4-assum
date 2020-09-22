<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer une adresse email.']),
                    new Email(['message' => 'Adresse email incorrecte.'])
                ],
                'required' => false
            ])
            ->add('pseudo',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un pseudo.']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le pseudo ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('nom',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un nom.']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le nom ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('prenom',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un prénom.']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le prénom ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('telephone',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un téléphone.']),
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'Le téléphone ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('plainPassword',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
                        'max' => 4096
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
