<?php

namespace App\Form;

use App\Entity\Assureur;
use App\Entity\Categorie;
use App\Entity\CategorieUC;
use App\Entity\FondsEuro;
use App\Entity\Gestion;
use App\Entity\Produit;
use App\Repository\FondsEuroRepository;
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
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Regex;

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
                'constraints' => [
                    new Length([
                        'max' => 300,
                        'maxMessage' => 'La description ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'help' => 'La description courte du produit est limitée à 300 caractères.',
                'required' => false
            ])
            ->add('versementInitial',NumberType::class,[
                'constraints' => [
                    new PositiveOrZero(['message' => 'Le versement initial doit être positif ou zéro.']),
                    new NotBlank(['message' => 'Le versement initial est manquant.'])
                ],
                'required' => false
            ])
            ->add('nbUcDisponibles',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ doit être renseigné.'])
                ],
                'help' => 'Exemple: +20 UC, +40 UC',
                'required' => false
            ])
            ->add('frais_adhesion',TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Les frais d\'adhéshion sont manquants.']),
                    new Regex([
                        'pattern' => '/(euros|%)/',
                        'message' => 'Il faut renseigner à la fin le type: euros ou %'
                    ])
                ],
                'help' => 'Il faut renseigner si c\'est en euros ou en %',
                'required' => false
            ])
            ->add('frais_versement', NumberType::class,[
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais de versement doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais de versement sont manquants.'])
                ],
                'help' => 'Le chiffre doit être multiplié par 100. Ex: 0,60% => 60',
                'required' => false,
            ])
            ->add('frais_gestion_euro',NumberType::class, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais de gestion doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais de gestion sont manquants.'])
                ],
                'help' => 'Le chiffre doit être multiplié par 100. Ex: 0,60% => 60',
                'required' => false,
            ])
            ->add('frais_gestion_uc',NumberType::class, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais de gestion doivent être positifs ou zéro.']),
                    new NotBlank(['message' => 'Les frais de gestion sont manquants.'])
                ],
                'help' => 'Le chiffre doit être multiplié par 100. Ex: 0,60% => 60',
                'required' => false,
            ])
            ->add('frais_arbitrage',NumberType::class, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Les frais d\'arbitrage doivent être positifs.']),
                    new NotBlank(['message' => 'Les frais d\'arbitrage sont manquants.'])
                ],
                'help' => 'Le chiffre doit être multiplié par 100. Ex: 0,60% => 60',
                'required' => false,
            ])
            ->add('assureur', EntityType::class, [
                'class' => Assureur::class,
                'choice_label' => 'nom'
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom'
            ])
            ->add('categories_uc', EntityType::class, [
                'class' => CategorieUC::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('gestion', EntityType::class, [
                'class' => Gestion::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('fonds_euro', EntityType::class,[
                'class' => FondsEuro::class,
                // Limiter le nombre de fonds euros affichés aux derniers deux années
                'query_builder' => function (FondsEuroRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.annee >= :this_year')
                        ->setParameter('this_year', date('Y')-3)
                        ->orderBy('u.annee', 'ASC');
                },
                'choice_label' => function ($fonds_euro) {
                    return $fonds_euro->getNom() . ' - ' . $fonds_euro->getAnnee() . ' - ' . $fonds_euro->getTauxPbFloat() . ' %';
                },
                'multiple' => true,
                'expanded' => true,
//                'group_by' => 'annee'
            ])
            ->add('label', ChoiceType::class, [
                'constraints' => [
                    new NotNull(['message' => 'Le produit détient-il le label de qualité ?'])
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                    ],
                'required' => false
            ])
            ->add('creation', DateType::class, [
                'constraints' => [
                    new NotNull(['message' => 'Merci de renseigner une date'])
                ],
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('image', FileType::class, [
                'label' => 'Télécharger une nouvelle image pour le logo:',
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
                        'maxSizeMessage' => 'La limite est de {{ limit }}.',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Les formats supportés: png, jpeg, jpg',
                    ])
                ],
                'help' =>'Type de fichiers (PNG, JPEG, JPG) - Taille maximum : 2MB'
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