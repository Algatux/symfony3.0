<?php
declare(strict_types=1);

namespace AppBundle\DataFixtures;

use AppBundle\DataFixtures\ORM\LoadApiKeyData;
use AppBundle\DataFixtures\ORM\LoadUserData;
use AppBundle\DataFixtures\ORM\LoadUserRegistryData;
use AppBundle\DataFixtures\ORM\LoadWalletData;
use AppBundle\DataFixtures\ORM\LoadWalletOperationData;

/**
 * Class FixtureOrderMap
 * @package AppBundle\Datafixtures
 */
class FixtureOrderMap
{

    private static $classOrderMap = [
        LoadUserData::class => 10,
        LoadApiKeyData::class => 20,
        LoadUserRegistryData::class => 30,
    ];

    public static function getClassOrder(string $className): int
    {
        if (! isset(self::$classOrderMap[$className])) {
            throw new \LogicException(sprintf('Thie fixture does not exists %s !!', $className));
        }

        return self::$classOrderMap[$className];
    }

}
