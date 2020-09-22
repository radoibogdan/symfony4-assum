<?php

namespace App\DataFixtures;

use App\Entity\AvisProduit;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AvisProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // Créer 60 avis pour les produits
        for ($i = 0; $i < 60; $i++) {
            $avisProduit = new AvisProduit();
            $avisProduit
                ->setNote($faker->numberBetween(1,10))
                ->setCommentaire($faker->sentence(15))
                ->setCreation($faker->dateTimeBetween('-6 months'))
            ;
            // Récupération aléatoire d'un user par une référence
            $userReference = 'user_' . $faker->numberBetween(0,9);
            /** @var User $user */
            $user = $this->getReference($userReference);
            $avisProduit->setAuteur($user);

            // Récupération aléatoire d'un produit par une référence
            $produitReference = 'produit_' . $faker->numberBetween(0,9);
            /** @var Produit $produit */
            $produit = $this->getReference($produitReference);
            $avisProduit->setProduit($produit);
            $manager->persist($avisProduit);
        }
        $manager->flush();
    }

    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     */
    public function getDependencies(){
        return [
            UserFixtures::class,
            ProduitFixtures::class
        ];
    }
}
