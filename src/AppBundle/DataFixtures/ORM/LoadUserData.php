<?php
//declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\AppBaseFixture;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AppBaseFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser1($manager);
        $this->loadUser2($manager);
    }

    private function loadUser1(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user1');
        $user->setEmail('user1@gmail.com');
        $user->setPlainPassword('user1_pass');
        $user->setEnabled(true);
//        $user->addRole(User::ROLE_DEFAULT);

        $manager->persist($user);
        $manager->flush($user);

        $this->addReference('user_user1',$user);
    }

    private function loadUser2(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user2');
        $user->setEmail('user2@gmail.com');
        $user->setPlainPassword('user2_pass');
        $user->addRole(User::ROLE_SUPER_ADMIN);


        $manager->persist($user);
        $manager->flush($user);

        $this->addReference('user_user2',$user);
    }

}
