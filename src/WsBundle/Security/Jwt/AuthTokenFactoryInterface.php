<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use AppBundle\Entity\User;
use WsBundle\Security\TokenInterface;

/**
 * Interface AuthTokenFactoryInterface
 * @package WsBundle\Security\Jwt
 */
interface AuthTokenFactoryInterface
{

    /**
     * @param User $user
     * @return TokenInterface
     */
    public function createToken(User $user);

}
