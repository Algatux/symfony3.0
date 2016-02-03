<?php
declare(strict_types=1);
namespace WsBundle\Security;

/**
 * Class ApiKeyValidator
 * @package WsBundle\Security
 */
class ApiKeyValidator
{

    const API_KEY_LENGTH = 36;

    const API_KEY_PATTERN = "/^([a-z0-9]{8})-([a-z0-9]{4})-([a-z0-9]{4})-([a-z0-9]{4})-([a-z0-9]{12})$/";
    
    /**
     * @param string $apiKey
     * @return bool
     */
    public function validateKey(string $apiKey): bool
    {

        if (strlen($apiKey) !== self::API_KEY_LENGTH) {
            return false;
        }

        if (false === preg_match(self::API_KEY_PATTERN, $apiKey)) {
            return false;
        }

        return true;

    }

}
