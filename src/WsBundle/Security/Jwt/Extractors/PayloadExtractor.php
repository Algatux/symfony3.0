<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PayloadExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
class PayloadExtractor extends AbstractExtractor
{

    const REGEX_JWT_PAYLOAD = '/^([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_+=]+)$/';

    /**
     * @param Request $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        $payload = json_decode($request->getContent(), true);

        return isset($payload['token']) ? $payload['token'] : '';
    }

    /**
     * @return string
     */
    protected function getRegexMatcher(): string
    {
        return self::REGEX_JWT_PAYLOAD;
    }

}
