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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

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
                    new PositiveOrZero(['message' => 'Les frais d\'adhéshion doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais d\'adhéshion sont manquants.'])
                ],
                'required' => false
            ])
            ->add('frais_versement', NumberType::class,[
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais de versement doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais de versement sont manquants.'])
                ],
                'required' => false
            ])
            ->add('frais_gestion',NumberType::class, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais de gestion doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais de gestion sont manquants.'])
                ]
            ])
            ->add('frais_arbitrage',NumberType::class, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais d\'arbitrage doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais d\'arbitrage sont manquants.'])
                ]
            ])
            ->add('assureur', EntityType::class, [
                'class' => Assureur::class,
                'choice_label' => 'nom'
            ])
            ->add('rendement',NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le rendement est manquant.'])
                ]
            ])
            ->add('categorie', EntityType::class, [
                    'class' => Categorie::class,
                    'choice_label' => 'nom'
                ])
            ->add('gestion', EntityType::class, [
                'class' => Gestion::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('label', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                    ],
                'required' => false
            ])
            ->add('creation', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (fichier JPG)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Les formats supportés : png, jpeg, jpg, jpe',
                    ])
                ],
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