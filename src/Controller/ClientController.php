<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClientController extends abstractController
{
    /**
     * @Route("/api/clients/{id}", name="client_by_id")
     */
    public function getClient(ClientRepository $articleRepository, SerializerInterface $serializer, $id)
    {
        $article = $articleRepository->find($id);

        $json = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/clients", name="clients")
     */
    public function getClients(ClientRepository $articleRepository, SerializerInterface $serializer)
    {

        $articles = $articleRepository->findAll();

        $json = $serializer->serialize($articles, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}