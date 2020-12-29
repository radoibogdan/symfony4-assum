<?php

namespace App\Form;

use App\Entity\CategorieUC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategorieUCFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un nom à votre catégorie d\'uc']),
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Le nom de la catégorie d\'uc ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategorieUC::class,
        ]);
    }
}
