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
        $response = $client->request('GET', 'http://localhost/app/api.php?action=get_list_bornes_zone&address='.$_GET["address"]);

        try {
            $contentType = $response->getHeaders()['content-type'][0];

            $content = $response->getContent($contentType);
            $contentJs = json_decode($content);
            return $this->render('map.html.twig', [
                'title' => 'Map Velib',
                'bornes' => $contentJs,
            ]);
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
            return null;
    }
}