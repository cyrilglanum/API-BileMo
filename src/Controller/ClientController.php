<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;

class ClientController extends abstractController
{
    /**
     * @Route("/api/v1/clients/{id}", name="client_by_id")
     */
    public function getClient(ClientRepository $clientRepository, SerializerInterface $serializer, $id)
    {
        $article = $clientRepository->find($id);

        $json = $serializer->serialize($article, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/clients", name="clients")
     */
    public function getClients(ClientRepository $clientRepository, SerializerInterface $serializer)
    {

        dd($this->getUser());
        if ($this->getUser()->getRoles()[0] === 'ROLE_CLIENT' && (string)$this->getUser()->getRoles() === '[ROLE_ADMIN]'){
            $clients = $clientRepository->findAll();

        $json = $serializer->serialize($clients, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
            }

    }
}