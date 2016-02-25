<?php
//declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use Lcobucci\JWT\Token;

/**
 * Interface AuthTokenValidatorInterface
 * @package WsBundle\Security\Jwt
 */
interface AuthTokenValidatorInterface
{

    /**
     * @param string $token
     * @return bool
     */
    public function validateRawToken(string $token): bool;

    /**
     * @param Token $token
     * @return bool
     */
    public function validateToken(Token $token): bool;

}
