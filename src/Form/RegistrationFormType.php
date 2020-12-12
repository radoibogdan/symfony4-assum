<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Regex;
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
                'first_options' => [
                    'label' => 'Mot de passe',
                    'help' => 'Le mot de passe doit être composé de 12 caractères dont un minimum : 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial'
                ],
                'second_options' => ['label' => 'Confirmation mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
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
                'required' => true
            ])
            ->add('consentement',CheckboxType::class,[
                'attr' => ['class' => 'is-flex-checkbox'],
                'constraints' => [
                    new IsTrue(['message' => 'Avez-vu lu notre politique de traitement de données ?'])
                ],
                'mapped' => false, // pour ne pas lier le consentement à la base de données
                'required' => false,
                'label' => 'Je confirme avoir lu la #DOCUMENTATION# concernant le traitement de mes données personnelles.'
            ])
            // Honey Pot - Anti spam, if value present in input present => probably a robot
            ->add('phone',HiddenType::class,[
                'mapped' => false,
                'constraints' => [
                    new Blank()
                ]
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
