<?php

namespace App\DataFixtures;

use App\Entity\Gestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GestionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $gestion1 = new Gestion();
        $gestion1->setNom('gestion pilotée');
        $manager->persist($gestion1);
        $this->addReference("gestion_1",$gestion1);

        $gestion2 = new Gestion();
        $gestion2->setNom('gestion libre');
        $manager->persist($gestion2);
        $this->addReference("gestion_2",$gestion2);

        $gestion3 = new Gestion();
        $gestion3->setNom('gestion profilée');
        $manager->persist($gestion3);
        $this->addReference("gestion_3",$gestion3);

        $manager->flush();
    }
}
