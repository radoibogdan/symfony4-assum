<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // Créer 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $hash = $this->passwordEncoder->encodePassword($user, 'user' . $i);
            $user
                ->setEmail('user' . $i . '@mail.com')
                ->setPassword($hash)
                ->setPseudo($faker->userName)
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setInscription($faker->dateTimeBetween('-1 year'))
            ;
            $manager->persist($user);
            // Lier la référence ($reference) à l'entité ($user), pour la récupérer dans d'autres fixtures
            $reference = 'user_' . $i;
            $this->addReference($reference,$user);
        }

        // Créer 2 admins
        for ($i = 0; $i < 2; $i++) {
            $admin = new User();
            $hash = $this->passwordEncoder->encodePassword($admin, 'admin' . $i);
            $admin
                ->setEmail('admin_annonce' . $i . '@mail.com')
                ->setPassword($hash)
                ->setPseudo($faker->userName)
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setInscription($faker->dateTimeBetween('-1 year'))
                ->setRoles(['ROLE_ADMIN'])
            ;
            $manager->persist($admin);
        }

        $manager->flush();
    }
}
