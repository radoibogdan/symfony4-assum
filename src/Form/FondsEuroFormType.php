<?php

namespace App\Form;

use App\Entity\FondsEuro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class FondsEuroFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label_fonds', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un libellé au fonds en euro.']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le libellé ne peut dépasser {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un nom au fonds en euro.']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le nom ne peut dépasser {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('annee', NumberType::class,[
                'constraints' => [
                    new Positive(['message' => 'L\'année doit être positive ou zéro.']),
                    new NotBlank(['message' => 'L\'année est manquante.'])
                ],
                'required' => false
            ])
            ->add('taux_pb', NumberType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Le taux de participation au bénéfice est manquant.'])
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FondsEuro::class,
        ]);
    }
}
