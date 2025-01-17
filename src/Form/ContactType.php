<?php

namespace App\Form;

use App\Entity\Contact;
// use Grafikart\RecaptchaBundle\Type\RecaptchaSubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom',TextType::class,[
                'required' => false
            ])
            ->add('nom',TextType::class,[
                'required' => false
            ])
            ->add('telephone',TextType::class,[
                'required' => false
            ])
            ->add('email',EmailType::class,[
                'required' => false
            ])
            ->add('message', TextareaType::class, [
                'help' => 'Ce message sera envoyé par email.',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un message.']),
                    new Length([
                        'min' => '5',
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                    ])
                ]
            ])
            ->add('consentement',CheckboxType::class,[
                'attr' => ['class' => 'is-flex-checkbox'],
                'constraints' => [
                    new IsTrue(['message' => 'La collecte de vos données personnelles nous permettra de vous contacter pour répondre à votre demande d\'information.'])
                ],
                'mapped' => false, // pour ne pas lier le consentement à la base de données
                'required' => false,
                'label' => 'Je consens à la collecte de mes données personnelles dans le cadre de cette demande de contact.'
            ])
            // Honey Pot - Anti spam, if value present in input present => probably a robot
            ->add('phone',HiddenType::class,[
                'mapped' => false,
                'constraints' => [
                    new Blank()
                ]
            ])
            /*->add('captcha',RecaptchaSubmitType::class,[
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
