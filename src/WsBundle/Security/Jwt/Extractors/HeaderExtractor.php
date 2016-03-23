<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class HeaderExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
class HeaderExtractor implements TokenExtractorInterface
{

    const REGEX_JWT_HEADER = "/^Bearer[\s]([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_+=]+)$/";

    /**
     * @param Request $request
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        $authHeader = $request->headers->get('Authorization', null, true);

        if (
            null === $authHeader ||
            ! preg_match(self::REGEX_JWT_HEADER, $authHeader, $matches) ||
            ! isset($matches[1])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function extract(Request $request): string
    {
        $authHeader = $request->headers->get('Authorization', null, true);

        if (
            null === $authHeader ||
            ! preg_match(self::REGEX_JWT_HEADER, $authHeader, $matches) ||
            ! isset($matches[1])
        ) {
            throw new BadCredentialsException('No Auth Token found.');
        }

        return $matches[1];
    }

}