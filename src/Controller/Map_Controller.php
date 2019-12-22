<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Map_Controller extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homepage()
    {
        return $this->render('homepage.html.twig', [
            'title' => 'Homepage',
        ]);
    }

    /**
     * @Route("/map", name="map")
     */
    public function map()
    {
        return $this->render('map.html.twig', [
            'title' => 'Map Velib',
            'bornes' => null,
        ]);
    }

    /**
     * @Route("/search_map", name="search")
     * @throws TransportExceptionInterface
     */
    public function search_map()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://api.boutin-p.fr/api.php?action=get_list_bornes_zone&address='.$_GET["address"]);

        try {
            $contentType = $response->getHeaders()['content-type'][0];
            $content = $response->getContent();

            return $this->render('map.html.twig', [
                'title' => 'Map Velib',
                'bornes' => json_decode($content),
            ]);
        } catch (ClientExceptionInterface $e) {
            echo "Error 1";
        } catch (RedirectionExceptionInterface $e) {
            echo "Error 2";
        } catch (ServerExceptionInterface $e) {
            echo "Error 3";
        } catch (TransportExceptionInterface $e) {
            echo $e;
        }
    }
}