<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '3',
                    'maxlenght' => '255',
                    'placeholder' => 'Entrez votre adresse email'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(['min' => 3, 'max' => 255])
                ]
            
            ])

            ->add('subject',  TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '3',
                    'maxlenght' => '50',
                    'placeholder' => 'Entrez le motif'
                ],
                'label' => 'Sujet',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 50])
                ]
            ])


            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '3',
                    'maxlenght' => '255',
                    'placeholder' => 'Entrez votre message'
                ],
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'onclick' => 'alert("Confirmer l\'envoi ?")'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])


            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-4'
                ],
                'label' => 'Envoyez votre message'
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
