<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/index');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testApiLink()
    {
        $client = static::createClient();
        $client->request('GET', 'https://gateway.marvel.com:443/v1/public/characters?ts=abcd&apikey=f66f630867f8865ff07e956fd0845f5c&hash=d905d3df4234dc3f0376715fbbb1970c
        ');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
