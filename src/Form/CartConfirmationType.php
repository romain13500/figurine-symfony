<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => "Entrez le nom du destinataire"
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse complète',
                'attr' => [
                    'placeholder' => "Entrez l'adresse de livraison'"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville de livraison',
                'attr' => [
                    'placeholder' => "Entrez la ville de livraison"
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => "Entrez le code postal"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Purchase::class
        ]);
    }
}
