<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PayloadExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
class PayloadExtractor implements TokenExtractorInterface
{

    const REGEX_JWT_PAYLOAD = "/^([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_+=]+)$/";

    /**
     * @param Request $request
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        $payload = json_decode($request->getContent(), true);

        if (
            null === $payload ||
            ! isset($payload['token']) ||
            ! preg_match(self::REGEX_JWT_PAYLOAD, $payload['token'], $matches) || ! isset($matches[1])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     * @return string
     * @throws BadCredentialsException
     */
    public function extract(Request $request): string
    {
        $payload = json_decode($request->getContent(), true);

        if (
            null === $payload ||
            ! isset($payload['token']) ||
            ! preg_match(self::REGEX_JWT_PAYLOAD, $payload['token'], $matches) || ! isset($matches[1])
        ) {
            throw new BadCredentialsException('No Auth Token found.');
        }

        return $payload['token'];
    }
}