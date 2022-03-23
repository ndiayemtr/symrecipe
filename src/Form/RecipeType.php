<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RecipeType extends AbstractType
{
    private $token;

public function __construct(TokenStorageInterface $token)
{
    $this->token = $token;
}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50'
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' =>'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\NotBlank()
            ]
            ])
            ->add('time', IntegerType::class, [ 
            'attr' => [
                'class' => 'form-control',
                'minLength' => '1',
                'maxLength' => '1441'
            ],
            'label' => 'Temps (en minutes)',
            'label_attr' => [
                'class' =>'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(1441)
            ]
            ])
            ->add('nbPersonne',  IntegerType::class, [ 
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '1',
                    'maxLength' => '50'
                ],
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' =>'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
                ])
            ->add('description', TextType::class, [ 
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' =>'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
                ])
            ->add('difficulte',  RangeType::class, [ 
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '1',
                    'maxLength' => '5'
                ],
                'label' => 'Difficulte',
                'label_attr' => [
                    'class' =>'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(6)
                ]
                ])
            ->add('price', MoneyType::class, [
                'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Prix',
            'label_attr' => [
                'class' =>'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(200)
            ]
            ])
            ->add('isFavorite', CheckboxType::class, [ 
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
                'label' => 'Favorite ?',
                'label_attr' => [
                    'class' =>'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ]
                ])
            //->add('createdAt')
            //->add('updatedAt')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
                },
                'label' => 'les ingredients ',
                'label_attr' => [
                    'class' =>'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Creer une recette'
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
