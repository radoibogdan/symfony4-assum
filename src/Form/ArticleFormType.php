<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un titre à l\'article.']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le titre ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' => false
            ])
            ->add('contenu', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiaquer le contenu de l\'article.'])
                ],
                'attr' => [
                    'placeholder' => 'Cette zone supporte le Markdown.'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
