<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends abstractController
{
    /**
     * @Route("/api/users/{id}", name="user_by_id")
     */
    public function getApiUser(UserRepository $userRepository, SerializerInterface $serializer, $id)
    {
        $article = $userRepository->find($id);

        $json = $serializer->serialize($article, 'json', ['groups' => 'user:read']);

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/users/all", name="users")
     */
    public function getApiUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();

        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/customer/{customer_id}/users", name="users")
     */
    public function getUserLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id)
    {
        $customer = $clientRepository->find($customer_id);

        $customer_users = $userRepository->findBy(
            ['client_id' => $customer->getId()]
        );

        $json = $serializer->serialize($customer_users, 'json', ['groups' => 'user:read']);

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

}