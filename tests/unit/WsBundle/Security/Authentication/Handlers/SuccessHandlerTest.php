<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Authentication\Handlers;

use AppBundle\Entity\User;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use WsBundle\Security\Authentication\Handlers\SuccessHandler;
use WsBundle\Security\Jwt\TokenGenerator;

class SuccessHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function test_on_authentication_success()
    {
        $user = new User();

        $jwttoken = new Token();

        $generator = $this->prophesize(TokenGenerator::class);
        $generator->createToken($user)
            ->shouldBeCalledTimes(1)
            ->willReturn($jwttoken);


        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()
            ->shouldBeCalledTimes(1)
            ->willReturn($user);

        $handler = new SuccessHandler($generator->reveal());
        $response = $handler->onAuthenticationSuccess(new Request(), $token->reveal());

        $this->assertEquals(201, $response->getStatusCode());

        $response_array = json_decode($response->getContent(),true);
        $this->assertEquals($jwttoken->__toString(), $response_array['token']);
    }

}
