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

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer une adresse email.']),
                    new Email   (['message' => 'Adresse email incorrecte.'])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}