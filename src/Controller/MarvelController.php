<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

/**
 * This Controller is a client for https://api-docs.igdb.com/ 
 */
class MarvelController extends AbstractController
{   
    /*
    * Param Api Key
    */
    private  $api ="bb80a4bd76d416af0b7bff625dd867b7";

    /*
    * Param Request Url
    */
    private $link = "https://api-v3.igdb.com/games";

    /*
    * method to make a find a list
    * @offset = int of offset
    */
    public function findALL(int $offset=0 )
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', $this->link,
        [ 
        'headers' => [
            'Content-Type' => 'application/json',
            'user-key' => $this->api,
        ],
        'body' => "fields id, genres.*,summary, artworks.*, storyline, name, platforms.*, screenshots.*; 
                    limit 20;
                    offset".$offset .";
                    where genres != n;
                    where artworks != n;
                    where platforms != n;
                    where screenshots != n;",

        ]);

        return $this->getJson($response);
    }

    /*
    * method to make a find one element
    * @id = int of id
    */
    public function findById(int $id )
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', $this->link.'/',
        [ 
        'headers' => [
            'Content-Type' => 'application/json',
            'user-key' => $this->api,
        ],
        'body' => "fields id, genres.*,summary, artworks.*, storyline, name, platforms.*, screenshots.*; 
                    where id = ".$id.";",
        ]);
        return $this->getJson($response);
    }

    /*
    * method to use decode json
    */
    public function getJson($response)
    {
                
        $content = json_decode($response->getContent());
        $response->cancel();
        return $content;
    }

    /**
     * All character for Marvel API
     * 
     * @Route("/index/{page}", name="index", requirements={"offset"="\d+"})
     */
    public function index($page = 5)
    {
        $offset = $page *20;
                
        $query = $this->findALL($offset);
     

        return $this->render('marvel/index.html.twig', [
            'objects' => $query,
            'nb_page' => $page,
        ]);
    }

    /**
     * Detail View for one character
     * 
     * @Route("/games/{id}", name="detailView",requirements={"id"="\d+"})
     */
    public function detailView(int $id)
    {
        $query = $this->findById($id);

        return $this->render('marvel/detail.html.twig', [
            'object' => $query,
        ]);
    }
}
