<?php

namespace App\Form;

use App\Entity\Assureur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssureurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le nom de l\'Assureur']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le nom de l\'Assureur ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('site',TextType::class,[
                'constraints' => [
                    new NotBlank(['message'=>'Veuillez indiquer le site internet de l\'Assureur.']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le site internet ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Assureur::class,
        ]);
    }
}
