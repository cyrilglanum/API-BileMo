<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
    /**
     * @Route("/articles/{id}", name="article_show")
     */
    public function showAction()
    {
        $article = new Article();
//        $article
//            ->setTitle('Mon premier article')
//            ->setContent('Le contenu de mon article.')
//        ;
//        $data = $this->get('jms_serializer')->serialize($article, 'json');
//
//        $response = new Response($data);
//        $response->headers->set('Content-Type', 'application/json');

//        return $response;
    }
}