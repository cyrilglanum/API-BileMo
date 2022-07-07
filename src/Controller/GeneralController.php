<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends abstractController
{
    /**
     * @Route("/documentation/api", name="documentation")
     */
    public function documentation(){

        return $this->render(
            'documentation.html.twig',
        );
    }

}