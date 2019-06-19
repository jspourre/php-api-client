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
    static private  $api ="bb80a4bd76d416af0b7bff625dd867b7";

    /*
    * Param Request Url
    */
    static public $link = "https://api-v3.igdb.com/games";

    /*
    * Param header for request
    */
    public $header;

    public function __construct()
    {
        $this->header = array(
            'Content-Type' => 'application/json',
            'user-key' => self::$api,
        );
    }

    /*
    * method to make a find all list
    * @offset = int of offset
    */
    public function findALL(int $offset=0 )
    {   
        if($offset > 150) //limation for free use of api
        {
            $offset = 150;
        }
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', self::$link,
        [ 
        'headers'   =>  $this->header ,
        'body'      =>  "fields id, genres.*,popularity ,summary, cover.*, artworks.*,
                        storyline, name, platforms.*, screenshots.*; 
                        limit 20;
                        offset".$offset .";
                        where genres != n;
                        where artworks != n;
                        where platforms != n;
                        where screenshots != n;
                        sort popularity desc;",
        ]);

        return $response;
    }

    /*
    * method to make a find one element
    * @id = int of id
    */
    public function findById(int $id )
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', self::$link.'/',
        [ 
        'headers'   =>  $this->header,
        'body'      =>  "fields id, genres.*, url, summary,first_release_date, artworks.*, 
                        storyline, name, platforms.*, screenshots.*; 
                        where id = ".$id.";",
        ]);
        return $response;
    }

    /*
    * Verification of status code and throw Exception if not 200 or 302
    */
    public function verifingStatus($response)
    {
        $statusCode = $response->getStatusCode();
        if(!in_array($statusCode , [200,302]))
        {
            throw new \Exception('Error with api. Status code is :'.$statusCode);
        }else{
            return true;
        }
        
    }

    /*
    * method to use decode json
    */
    public function getJsonDecode($response)
    {
        if($this->verifingStatus($response))
        {
            $content = json_decode($response->getContent());
            $response->cancel();
            return $content;
        }
    }

    /**
     * All character for Marvel API
     * 
     * @Route("{page}", name="index", requirements={"offset"="\d+"})
     */
    public function index($page = 5)
    {
        $offset = ($page-1) *20;        
        $query = $this->getJsonDecode($this->findALL($offset));
     
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
        $query = $this->getJsonDecode($this->findById($id));
        return $this->render('marvel/detail.html.twig', [
            'object' => $query,
        ]);
    }
}
