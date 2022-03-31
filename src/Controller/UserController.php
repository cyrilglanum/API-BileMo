<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends abstractController
{
    /**
     * @Route("/api/users/{id}", name="user_by_id")
     */
    public function getArticle(UserRepository $articleRepository, SerializerInterface $serializer, $id)
    {
        $article = $articleRepository->find($id);

        $json = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/users", name="users")
     */
    public function getArticles(UserRepository $articleRepository, SerializerInterface $serializer)
    {

        $articles = $articleRepository->findAll();

        $json = $serializer->serialize($articles, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}