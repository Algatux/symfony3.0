<?php

namespace unit\WsBundle\Security\Jwt;

use AppBundle\Entity\User;
use Carbon\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Prophecy\Argument;
use WsBundle\Security\Jwt\TokenGenerator;

class TokenGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function test_create_token()
    {

        $user = new User();
        $user->setId(1);
        $user->setUsername('user1');

        $signer = new Sha256();
        $secret = 'test_sign_secret';

        $config = [
            'issuer' => 'me',
            'audience' => 'me',
            'appid' => 10,
            'expire' => 60,
            'notbefore' => 5,
        ];

        $issued = Carbon::now();
        $expire = Carbon::now()->addSeconds((int) $config['expire']);
        $notBefore = Carbon::now()->addSeconds((int) $config['notbefore']);

        $builder = $this->prophesize(Builder::class);
        $builder->setIssuer('me')->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->setAudience('me')->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->setId(10, true)->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->setIssuedAt($issued->getTimestamp())->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->setNotBefore($notBefore->getTimestamp())->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->setExpiration($expire->getTimestamp())->shouldBeCalledTimes(1)->willReturn($builder->reveal());

        $builder->set(
            'data',
            [
                "uid" => $user->getId(),
                "uidentifier" => $user->getUsername(),
            ]
        )->shouldBeCalledTimes(1)->willReturn($builder->reveal());

        $builder->sign($signer, $secret)->shouldBeCalledTimes(1)->willReturn($builder->reveal());
        $builder->getToken()->shouldBeCalledTimes(1)->willReturn(new Token());

        $generator = new TokenGenerator($builder->reveal(), $signer, $secret, $config);
        $generator->createToken($user);
    }
    
}
