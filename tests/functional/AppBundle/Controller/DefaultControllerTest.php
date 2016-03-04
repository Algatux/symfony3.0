<?php

namespace Tests\AppBundle\Controller;

use Tests\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
