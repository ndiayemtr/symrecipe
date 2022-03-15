<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;

use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker; 

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 50; $i++) { 
            $ingredient = new Ingredient();
             $ingredient->setName($this->faker->word())
                   ->setPrice(\mt_rand(0, 100));
            
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
                    ->setIsFavorite(\mt_rand(0, 1) == 1 ? true : false);

            for ($k=0; $k < \mt_rand(5, 15); $k++) { 
                $recette->addIngredient($ingredients[\mt_rand(0, \count($ingredients) - 1) ]);
            }

            $manager->persist($recette);
                    
        }

        
        
        $manager->flush();
    }
}
