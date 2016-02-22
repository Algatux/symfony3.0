<?php

namespace Tests\AppBundle\Controller;

use Tests\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony 3.0', $crawler->filter('div.jumbotron')->text());
    }
}
