<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;


class MarvelController extends AbstractController
{   
    private  $api ="00281aa6705babdd74b9e29b3013c965";
    private  $secret = "8e67059aef511b5a967e57293dcaf267df3b0d91";
    private $ts ;

    function __construct() 
    {
        $this->ts = strval(time());
    }

    /*
    * method to use curl to make a request and return a json
    */
    public function getJson($link) 
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $link,
        [ "query" => [
            "apikey" => $this->api,
            "ts" => $this->ts,
            "hash" => $this->makeHash(),
        ],
        'headers' => [
            'Content-Type' => 'text/plain',
            'Accept'=> '*/*',
            'Accept-Encoding' => 'gzip',

        ],
        ]);  
        $content = $response->getContent(false);
       
        return $response;
    }

    /*
    * method to make an md5 hash
    */
    private function makeHash() : ?string
    {
        $ts = time();
        $hash = md5($this->ts.$this->secret.$this->api);
        return $hash;
    }

    /**
     * All character for Marvel API
     * 
     * @Route("/index/{offset}", name="index", requirements={"offset"="\d+"})
     */
    public function index($offset = 100)
    {

        $link = "http://gateway.marvel.com/v1/public/characters?limit=20&offset=".$offset;
        
        $query = $this->getJson($link);


        return $this->render('marvel/index.html.twig', [
            'controller_name' => 'IndexController',
            'objects' => $query,
        ]);
    }

    /**
     * Detail View for one character
     * 
     * @Route("/characters/{id}", name="detailView",requirements={"id"="\d+"})
     */
    public function detailView($id)
    {

        $link = "http://gateway.marvel.com/v1/public/characters/".$id."?";
        
        $query = $this->getJson($link);

        return $this->render('marvel/detail.html.twig', [
            'id' => $id,
            'object' => $query,
        ]);
    }
}
