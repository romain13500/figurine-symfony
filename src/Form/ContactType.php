<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;


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
                    new Length(['min' => 3,
                        'minMessage' => 'Entrez le sujet de votre message !',
                        'max' => 50])
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
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Entrez votre message !'
                    ])
                ]
            ])


            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-4'
                ],
                'label' => 'Envoyez votre message'
            ])
            
            
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'app_contact',
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
