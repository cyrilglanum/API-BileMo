<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Exception;
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
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les clients.", 403, ["Content-Type" => "application/json"]);
        }

        $role = $this->checkRole($this->getUser());

        if ($role === 'admin') {
            $client = $clientRepository->find($id);
        } else {
            $client = $clientRepository->find($id);
            if ($client->getId() != $this->getUser()->getClientId()) {
                return new Response("Vous n'êtes pas autorisé à voir ce client.", 403, ["Content-Type" => "application/json"]);
            }
        }

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
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les clients.", 403, ["Content-Type" => "application/json"]);
        }
        $role = $this->checkRole($this->getUser());

        if ($role === 'admin') {
            $clients = $clientRepository->findAll();

            if ($clients === null) {
                return new Response("Aucun clients trouvé", 200, [
                    "Content-Type" => "application/json"
                ]);
            }

            $json = $serializer->serialize($clients, 'json');
        } else {
            $client = $clientRepository->findBy(['id' => $this->getUser()->getClientId()]);
            if ($client[0]->getId() != $this->getUser()->getClientId()) {
                return new Response("Vous n'êtes pas autorisé à voir les clients.", 403, ["Content-Type" => "application/json"]);
            }

            if ($client === null) {
                return new Response("Aucun clients trouvé", 200, [
                    "Content-Type" => "application/json"
                ]);
            }

            $json = $serializer->serialize($client, 'json');
        }


        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    private function checkRole(?\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $maxRole = null;

        foreach ($user->getRoles() as $role) {
            if ($role === 'ROLE_ADMIN') {
                $maxRole = 'admin';
            } else if ($role === 'ROLE_CLIENT') {
                if ($maxRole === null) {
                    $maxRole = 'client';
                }
            }
        }

        return $maxRole;
    }
}