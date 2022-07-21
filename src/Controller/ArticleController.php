<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends abstractController
{
    /**
     * @Route("/api/v1/products/{id}", name="productById", methods={"GET"})
     */
    public function getArticle(ArticleRepository $articleRepository, SerializerInterface $serializer, $id)
    {
        $article = $articleRepository->find($id);

        $json = $serializer->serialize($article, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/products", name="products", methods={"GET"})
     */
    public function getArticles(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {

        $articles = $articleRepository->findAll();

        $json = $serializer->serialize($articles, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}