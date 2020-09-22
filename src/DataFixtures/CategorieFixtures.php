<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categorie1 = new Categorie();
        $categorie1->setNom('fonds en euros');
        $manager->persist($categorie1);
        $this->addReference("categorie_1",$categorie1);

        $categorie2 = new Categorie();
        $categorie2->setNom('fonds en unités de compte');
        $manager->persist($categorie2);
        $this->addReference("categorie_2",$categorie2);

        $manager->flush();
    }
}
