<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Jwt\Extractors;

use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use WsBundle\Security\Jwt\Extractors\PayloadExtractor;

class PayloadExtractorTest extends \PHPUnit_Framework_TestCase
{

    public function test_can_handle()
    {

        $request = $this->prophesize(Request::class);
        $request->getContent(Argument::any())
            ->shouldBeCalledTimes(1)
            ->willReturn('{"token":"xxx.xxx.xxx"}');

        $extractor = new PayloadExtractor();

        $this->assertTrue($extractor->canHandle($request->reveal()));

    }

    /**
     * @dataProvider fakeTokenProvider
     */
    public function test_can_not_handle($token)
    {
        $request = $this->prophesize(Request::class);
        $request->getContent(Argument::any())
            ->shouldBeCalledTimes(1)
            ->willReturn('{"token":"'.$token.'"}');

        $extractor = new PayloadExtractor();

        $this->assertFalse($extractor->canHandle($request->reveal()));

    }

    public function test_can_not_handle_not_specified_token()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent(Argument::any())
            ->shouldBeCalledTimes(1)
            ->willReturn('{"toLkien":"xxx.xxx.xxx"}');

        $extractor = new PayloadExtractor();

        $this->assertFalse($extractor->canHandle($request->reveal()));

    }

    public function fakeTokenProvider()
    {
        return [
            ['xxx.xxx.xxx.xxx'],
            ['xxx.xxx'],
        ];
    }

    public function test_extract()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent(Argument::any())
            ->shouldBeCalledTimes(1)
            ->willReturn('{"token":"xxx.xxx.xxx"}');

        $extractor = new PayloadExtractor();

        $token = $extractor->extract($request->reveal());

        $this->assertEquals('xxx.xxx.xxx',$token);
    }

}