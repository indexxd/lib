<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    private $users = [
        ["aaaaa@gogo.com", "admin", "password", "ROLE_ADMIN"],
        ["bbbbb@gogo.com", "franta", "password", "ROLE_CUSTOMER"],
        ["ccccc@gogo.com", "pepa", "password", "ROLE_CUSTOMER"],
        // ["ddddd@gogo.com", "jednicka", "password", "ROLE_STUDENT"],
        // ["eeeee@gogo.com", "lol", "password", "ROLE_STUDENT"],
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    

    public function load(ObjectManager $manager)
    {        
        foreach ($this->users as $user_loaded) {
            $user = new User();
            $user->setEmail($user_loaded[0]);
            $user->setUsername($user_loaded[1]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user_loaded[2]));
            $user->setRoles([$user_loaded[3]]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}