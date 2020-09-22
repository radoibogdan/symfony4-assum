<?php

namespace App\DataFixtures;

use App\Entity\Assureur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssureurFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 10 assureurs
        for ($i = 0; $i < 10; $i++) {
            $assureur = new Assureur();
            $nom = $faker->word();
            $assureur
                ->setNom($nom)
                ->setSite('www.' . $nom . '.com')
            ;
            $manager->persist($assureur);
            // Lier la référence ($reference) à l'entité ($assureur), pour la récupérer dans d'autres fixtures
            $reference = 'assureur_' . $i;
            $this->addReference($reference, $assureur);
        }

        $manager->flush();
    }
}
