<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figurines;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigurineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la figurine',
                'attr' => [
                    'placeholder' => 'Entrez le nom de la figurine'
                ]
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Image de la figurine',
                'attr' => ['placeholder' => 'Entrez une URL d\'image ']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de la figurine',
            ])

            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description de la figurine',
                'attr' => [
                    'placeholder' => 'décrivez la figurine'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisissez une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return($category->getCategoryName());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figurines::class,
        ]);
    }
}
