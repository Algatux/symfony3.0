<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Jwt\Extractors;

use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use WsBundle\Security\Jwt\Extractors\HeaderExtractor;

class HeaderExtractorTest extends \PHPUnit_Framework_TestCase
{

    public function test_can_handle()
    {

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer xxx.xxx.xxx');

        $extractor = new HeaderExtractor();

        $this->assertTrue($extractor->canHandle($request));

    }

    /**
     * @dataProvider fakeTokenProvider
     */
    public function test_can_not_handle($token)
    {

        $request = new Request();
        $request->headers->set('Authorization', $token);

        $extractor = new HeaderExtractor();

        $this->assertFalse($extractor->canHandle($request));

    }

    public function fakeTokenProvider()
    {
        return [
            ['xxx.xxx.xxx'],
            ['Bearer xxx.xxx'],
            ['Bear xxx.xxx.xxx'],
        ];
    }

    public function test_extract()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer xxx.xxx.xxx');

        $extractor = new HeaderExtractor();

        $token = $extractor->extract($request);

        $this->assertEquals('xxx.xxx.xxx',$token);
    }
    
}
