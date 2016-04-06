<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class HeaderExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
class HeaderExtractor extends AbstractExtractor
{

    const REGEX_JWT_HEADER = '/^Bearer[\s]([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_+=]+)$/';

    /**
     * @param Request $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        return $request->headers->get('Authorization', '', true);
    }

    /**
     * @return string
     */
    protected function getRegexMatcher(): string
    {
        return self::REGEX_JWT_HEADER;
    }

}
