<?php
declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\AppBaseFixture;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use WsBundle\Entity\ApiKey;

/**
 * Class LoadApiKeyData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadApiKeyData extends AppBaseFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadKeysForUser1($manager);
    }

    private function loadKeysForUser1(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('user_user1');

        $key = new ApiKey();
        $key->setDevice('LG G4');
        $key->setDeviceIdentifier('LG_G4_user_1');
        $key->setApiKey('32db4703-558f-4363-a409-733f80b95e8c');
        $key->setUser($user);

        $manager->persist($key);

        $key1 = new ApiKey();
        $key1->setDevice('Apple iPhone 5');
        $key1->setDeviceIdentifier('Apple_iPhone5_user_1');
        $key1->setApiKey('3d37a721-5074-4293-aec3-214e668fb199');
        $key1->setUser($user);

        $manager->persist($key1);

        $user->addApiKey($key);
        $user->addApiKey($key1);

        $manager->flush($user, $key, $key1);

        $this->setReference('apiKey_LGG4_user1',$key);
        $this->setReference('apiKey_AppleiPhine5_user1',$key);

    }

}
