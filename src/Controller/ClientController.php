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
     * @Route("/api/v1/clients/{id}", name="clientById", methods={"GET"})
     */
    public function getClient(ClientRepository $clientRepository, SerializerInterface $serializer, $id)
    {
        $client = $clientRepository->find($id);

        if ($client === null) {
            return new Response("Aucun client trouvé", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $json = $serializer->serialize($client, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/clients", name="clients", methods={"GET"})
     */
    public function getClients(ClientRepository $clientRepository, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $this->getUser()->getRoles());
        $clients = $clientRepository->findAll();

        if($clients === null){
            return new Response("Aucun clients trouvé", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $json = $serializer->serialize($clients, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}