<?php

namespace unit\WsBundle\Security\Jwt;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use WsBundle\Security\Jwt\TokenValidator;

class TokenValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function test_validate_token()
    {
        $signer = new Sha256();
        $secret = 'test_sign_secret';

        $config = [
            'issuer' => 'me',
            'audience' => 'me',
            'appid' => 10,
            'expire' => 60,
            'notbefore' => 5,
        ];

        $validationData = $this->prophesize(ValidationData::class);
        $validationData->setAudience('me')->shouldBeCalledTimes(1);
        $validationData->setIssuer('me')->shouldBeCalledTimes(1);
        $validationData->setId(10)->shouldBeCalledTimes(1);

        $token = $this->prophesize(Token::class);
        $token->validate($validationData)->shouldBeCalledTimes(1)->willReturn(true);
        $token->verify($signer, $secret)->shouldBeCalledTimes(1)->willReturn(true);

        $validator = new TokenValidator($validationData->reveal(), $signer, $secret, $config);
        $validator->validateToken($token->reveal());
    }

}
