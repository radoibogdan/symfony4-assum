<?php

namespace App\Form;

use App\Entity\AvisProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvisProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentaire',TextareaType::class,[
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer un commentaire.']),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le commentaire ne peut contenir plus de {{ limit }} caractères.'
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Le commentaire ne peut contenir en dessous de {{ limit }} caractères.'
                    ])
                ],
                'required' => false,
                'attr' => [
                    'placeholder' => 'On veut connaître votre ressenti sur ce produit. Ce que vous pensez être un atout mais aussi les inconvénients.'
                ]
            ])
            ->add('note', RangeType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'oninput'   => 'setNoteJs(this.value)'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AvisProduit::class,
        ]);
    }
}
