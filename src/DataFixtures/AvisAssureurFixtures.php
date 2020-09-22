<?php

namespace App\DataFixtures;

use App\Entity\Assureur;
use App\Entity\AvisAssureur;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AvisAssureurFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Créer 30 avis pour les assureurs
        for ($i = 0; $i < 30; $i++) {
            $avisAssureur = new AvisAssureur();
            $avisAssureur
                ->setNote($faker->numberBetween(1,10))
                ->setAvis($faker->sentence(15))
                ->setCreation($faker->dateTimeBetween('-6 months'))
            ;
            // Récupération aléatoire d'un user par une référence
            $userReference = 'user_' . $faker->numberBetween(0,9);
            /** @var User $user */
            $user = $this->getReference($userReference);
            $avisAssureur->setAuteur($user);

            // Récupération aléatoire d'un assureur par une référence
            $assureurReference = 'assureur_' . $faker->numberBetween(0,9);
            /** @var Assureur $assureur */
            $assureur = $this->getReference($assureurReference);
            $avisAssureur->setAssureur($assureur);
            $manager->persist($avisAssureur);
        }
        $manager->flush();
    }

    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     * needs implements DependentFixtureInterface
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AssureurFixtures::class
        ];
    }
}
