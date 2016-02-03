<?php
declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\AppBaseFixture;
use AppBundle\Entity\UserRegistry;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserRegistryData extends AppBaseFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $this->loadFabrizioMarangoni($manager);

        $this->loadMarcellaRegondi($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadFabrizioMarangoni(ObjectManager $manager)
    {
        $registry = new UserRegistry();
        $registry->setName('Fabrizio');
        $registry->setSurname('Marangoni');
        $registry->setUser($this->getReference('user_user1'));

        $manager->persist($registry);
        $manager->flush($registry);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadMarcellaRegondi(ObjectManager $manager)
    {
        $registry = new UserRegistry();
        $registry->setName('Marcella');
        $registry->setSurname('Regondi');
        $registry->setUser($this->getReference('user_user2'));

        $manager->persist($registry);
        $manager->flush($registry);
    }

}
