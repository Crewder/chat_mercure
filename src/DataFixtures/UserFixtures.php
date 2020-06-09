<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user2 = new User();

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'password'
        ));

        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'password'
        ));

        $user->setEmail('steven@gmail.com');
        $user->setRoles(['ROLE_USER']);

        $user2->setEmail('julien@gmail.com');
        $user2->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
    }
}