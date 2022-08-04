<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Exception;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends abstractController
{
    /**
     * @Route("/api/v1/articles/{id}", name="articleById", methods={"GET"})
     */
    public function getArticle(ArticleRepository $articleRepository, SerializerInterface $serializer, $id)
    {
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les articles.", 403, ["Content-Type" => "application/json"]);
        }

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
     * @Route("/api/v1/articles", name="articles", methods={"GET"})
     */
    public function getArticles(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les articles.", 403, ["Content-Type" => "application/json"]);
        }

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