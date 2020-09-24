<?php

namespace App\Form;

use App\Entity\Assureur;
use App\Entity\Categorie;
use App\Entity\Gestion;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer un titre au produit.']),
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'Le nom ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('description',TextareaType::class,[
                'required' => false
            ])
            ->add('frais_adhesion',NumberType::class, [
                'constraints' =>[
                    new Positive(['message' => 'Les frais d\'adhéshion doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais d\'adhéshion sont manquants.'])
                ],
                'required' => false
            ])
            ->add('frais_versement', NumberType::class,[
                'constraints' => [
                    new Positive(['message' => 'Les frais de versement doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais de versement sont manquants.'])
                ],
                'required' => false
            ])
            ->add('frais_gestion',NumberType::class, [
                'constraints' => [
                    new Positive(['message' => 'Les frais de gestion doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais de gestion sont manquants.'])
                ]
            ])
            ->add('frais_arbitrage',NumberType::class, [
                'constraints' => [
                    new Positive(['message' => 'Les frais d\'arbitrage doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais d\'arbitrage sont manquants.'])
                ]
            ])
            ->add('assureur', EntityType::class, [
                'class' => Assureur::class,
                'choice_label' => 'nom'
            ])
            ->add('rendement',NumberType::class, [
                'constraints' => [
                    new Positive(['message' => 'Le rendement doit être positif.']),
                    new NotBlank(['message' => 'Le rendement est manquant.'])
                ]
            ])
            ->add('categorie', EntityType::class, [
                    'class' => Categorie::class,
                    'choice_label' => 'nom'
                ])
            ->add('gestion', EntityType::class, [
                'class' => Gestion::class,
                'choice_label' => 'nom'
            ])
            ->add('label', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                    ],
                'required' => false
            ])
            ->add('creation', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}