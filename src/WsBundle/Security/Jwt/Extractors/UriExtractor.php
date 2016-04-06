<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class UriExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
class UriExtractor extends AbstractExtractor
{

    const REGEX_JWT = '/^([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_+=]+)$/';

    /**
     * @param Request $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        return $request->query->get('token','');
    }

    /**
     * @return string
     */
    protected function getRegexMatcher(): string
    {
        return self::REGEX_JWT;
    }

}
