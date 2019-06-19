<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\MarvelController;

class MarvelControllerTest extends WebTestCase
{
    public function testLink()
    {
        $re = '/^http(s)?:\/\/((\d+\.\d+\.\d+\.\d+)|(([\w-]+\.)+([a-z,A-Z][\w-]*)))(:[1-9][0-9]*)?(\/([\w-.\/:%+@&=]+[\w- .\/?:%+@&=]*)?)?(#(.*))?$/m';
        $this->assertRegExp($re, MarvelController::$link);
    }

    public function testFindAll()
    {
        $req = new MarvelController();
        $this->assertEquals(200, $req->findALL()->getStatusCode());
        $this->assertNotEquals(401,$req->findALL()->getStatusCode() );

    }
    
    public function testFindById()
    {

        $req = new MarvelController();
        $this->assertNotInstanceOf(\TypeError::class, $req->findById("56529656"));
        $this->assertEquals(200, $req->findById(74701)->getStatusCode());
        $this->assertNotEquals(401,$req->findById(74701)->getStatusCode() );

    }

    public function testVerifingStatus()
    {   
       $response = new MarvelController();
       $this->assertNotFalse(
           $response->verifingStatus($response->findAll())
       );
    }

    public function testGetJsonDecode()
    {
        $req = new MarvelController();
        $this->assertNotFalse(is_array(
            $req->getJsonDecode($req->findById(74701))
        ));
    }

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testDetailView()
    {
        $client = static::createClient();
        $client->request('GET', '/games/74701');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}



