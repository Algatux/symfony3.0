<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Jwt\Extractors;

use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use WsBundle\Security\Jwt\Extractors\UriExtractor;

class UriExtractorTest extends \PHPUnit_Framework_TestCase
{

    public function test_can_handle()
    {

        $request = new Request();
        $request->query->set('token', 'xxx.xxx.xxx');

        $extractor = new UriExtractor();

        $this->assertTrue($extractor->canHandle($request));

    }

    /**
     * @dataProvider fakeTokenProvider
     */
    public function test_can_not_handle($token)
    {

        $request = new Request();
        $request->query->set('token', $token);

        $extractor = new UriExtractor();

        $this->assertFalse($extractor->canHandle($request));

    }

    public function fakeTokenProvider()
    {
        return [
            ['xxx.xxx'],
            ['xxx.xxx.xxx.x'],
        ];
    }

    public function test_extract()
    {
        $request = new Request();
        $request->query->set('token', 'xxx.xxx.xxx');

        $extractor = new UriExtractor();

        $token = $extractor->extract($request);

        $this->assertEquals('xxx.xxx.xxx',$token);
    }

}