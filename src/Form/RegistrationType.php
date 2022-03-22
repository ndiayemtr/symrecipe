<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fullName', TextType::class, [
            'attr' => [
            'class' => 'form-control',
            'minLength' => '2',
            'maxLength' => '50'
        ],
        'label' => 'Nom et prénom',
        'label_attr' => [
            'class' =>'form-label mt-4'
        ],
        'constraints' => [
            new Assert\Length(['min' => 2, 'max' => 50]),
            new Assert\NotBlank()
        ]
        ])
            ->add('email', EmailType::class, [
                'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50'
            ],
            'label' => 'Email',
            'label_attr' => [
                'class' =>'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\NotBlank(),
                new Assert\Email()
            ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50'
            ],
            'label' => 'Pseudo (facultattive)',
            'label_attr' => [
                'class' =>'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
            ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' =>'Mot de passe', 
                    'attr' => [
                        'class' =>'form-control mt-4'
                    ],
                ], 
                'second_options' => [
                    'label' => 'confirmation du mot de passe',
                    'attr' => [
                        'class' =>'form-control mt-4'
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas'
            ])
            
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
