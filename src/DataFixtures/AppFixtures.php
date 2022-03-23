<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;

use Faker\Generator;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker; 

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
           
            $user->setFullName($this->faker->word())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() :  null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPassword('password');

                $users[] = $user;

                $manager->persist($user);

        } 

        for ($i=1; $i <= 50; $i++) { 
            $ingredient = new Ingredient();
             $ingredient->setName($this->faker->word())
                   ->setPrice(\mt_rand(0, 100))
                   ->setUser($users[\mt_rand(0, \count($users) - 1) ]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        for ($j=0; $j < 25 ; $j++) { 
            $recette = new Recette();
            $recette->setName($this->faker->word())
                    ->setTime(\mt_rand(0, 1) == 1 ? \mt_rand(1, 1440) : null)
                    ->setNbPersonne(\mt_rand(0, 1) == 1 ? \mt_rand(1, 50) : null)
                    ->setDifficulte(\mt_rand(0, 1) == 1 ? \mt_rand(1, 5) : null)
                    ->setDescription($this->faker->text(300))
                    ->setPrice(\mt_rand(0, 1) == 1 ? \mt_rand(1, 1001) : null)
                    ->setIsFavorite(\mt_rand(0, 1) == 1 ? true : false)
                    ->setUser($users[\mt_rand(0, \count($users) - 1) ]);

            for ($k=0; $k < \mt_rand(5, 15); $k++) { 
                $recette->addIngredient($ingredients[\mt_rand(0, \count($ingredients) - 1) ]);
            }

            $manager->persist($recette);
                    
        } 

       

        
        
        $manager->flush();
    }
}
