<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends abstractController
{
    /**
     * @Route("/api/v1/products/{id}", name="article_by_id")
     */
    public function getArticle(ArticleRepository $articleRepository, SerializerInterface $serializer, $id)
    {
        $article = $articleRepository->find($id);

        $json = $serializer->serialize($article, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/v1/products", name="articles")
     */
    public function getArticles(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {

        $articles = $articleRepository->findAll();

        $json = $serializer->serialize($articles, 'json', ['groups' => 'article:read']);

        $response = new Response($json, 200,[
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}