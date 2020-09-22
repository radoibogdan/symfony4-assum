<?php

namespace App\DataFixtures;

use App\Entity\Assureur;
use App\Entity\Categorie;
use App\Entity\Gestion;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Créer 20 produits
        for ($i = 0; $i < 20; $i++) {
            $produit = new Produit();
            $produit
                ->setTitre($faker->sentence(2))
                ->setDescription($faker->realText(20))
                ->setFraisAdhesion($faker->numberBetween(0,100))
                ->setFraisVersement($faker->numberBetween(0,500))
                ->setFraisGestion($faker->numberBetween(0,60))
                ->setFraisArbitrage($faker->numberBetween(0,100))
                ->setRendement($faker->numberBetween(50,300))
                ->setLabel($faker->boolean())
                ->setCreation($faker->dateTimeBetween('-1year'))
            ;

            // Récupération aléatoire d'un assureur par une référence
            $assureurReference = 'assureur_' . $faker->numberBetween(0,9);
            /** @var Assureur $assureur */
            $assureur = $this->getReference($assureurReference);
            $produit->setAssureur($assureur);

            // Récupération aléatoire d'une catégorie par une référence
            $categorieReference = 'categorie_' . $faker->numberBetween(1,2);
            /** @var Categorie$categorie */
            $categorie = $this->getReference($categorieReference);
            $produit->setCategorie($categorie);

            // Récupération aléatoire d'une gestion par une référence
            $gestionReference = 'gestion_' . $faker->numberBetween(1,3);
            /** @var Gestion $gestion */
            $gestion = $this->getReference($gestionReference);
            $produit->setGestion($gestion);
            $manager->persist($produit);

            // Lier la référence ($reference) à l'entité ($produit), pour la récupérer dans d'autres fixtures
            $reference = 'produit_' . $i;
            $this->addReference($reference,$produit);
        }
        $manager->flush();
    }

    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     */
    public function getDependencies(){
        return [
            AssureurFixtures::class,
            CategorieFixtures::class,
            GestionFixtures::class
        ];
    }
}