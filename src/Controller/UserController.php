<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Services\UserService;
use Doctrine\DBAL\Types\Type;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class UserController extends abstractController
{
    /**
     * @Route("/api/v1/users/{id}", name="user_by_id")
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
     * @Route("/api/v1/users/all", name="users")
     */
    public function getApiUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
                dd("ici");

        $users = $userRepository->findAll();

        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/v1/customer/{customer_id}/users", name="usersByCustomer")
     */
    public function getUsersLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id): Response
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

    /**
     * @Route("/api/v1/customer/{customer_id}/user/{id}", name="userFromCustomer")
     */
    public function getUserLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id, $id): Response
    {
        $customer = $clientRepository->find($customer_id);

        $customer_user = $userRepository->findOneBy([
                'client_id' => $customer->getId(),
                'id' => $id]
        );

        $json = $serializer->serialize($customer_user, 'json', ['groups' => 'user:read']);

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/v1/customer/add/user", name="addUserToCustomer")
     */
    public function addUserLinkedToCustomer(Request $request, UserService $userService): Response
    {
        $user_infos = json_decode($request->request->get('data'));

        $user = new Users();
        $user->setLastname($user_infos->user->lastname);
        $user->setFirstname($user_infos->user->firstname);
        $user->setEmail($user_infos->user->email);
        $user->setPostalcode($user_infos->user->postal_code);
        $user->setVille($user_infos->user->ville);
        $user->setActif($user_infos->user->actif);
        $user->setRoles([str_replace('\'', "\"",$user_infos->user->roles)]);
        $user->setClientId($user_infos->user->client_id);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setUpdatedAt(new \DateTimeImmutable('now'));

        try {
            $emailExists = $userService->mailExists($user_infos->user->email);

            if ($emailExists === false) {
                //controle des données envoyées sauf mail + flush du model.
                $userService->add($user);
            }
        } catch (Exception $e) {
            return new Response($e->getMessage(), 403, [
                "Content-Type" => "application/json"
            ]);
        }

        return new Response("L'utilisateur a bien été ajouté.", 200, [
            "Content-Type" => "application/json"
        ]);
    }

    /**
     * @Route("/api/v1/customer/delete/user", name="deleteUser")
     */
    public function deleteUserLinkedToCustomer(Request $request, UserService $userService): Response
    {
        $user_infos = json_decode($request->request->get('user'));

        try {
            $user = $userService->findUser($user_infos->user->email);

            if ($user) {
                $userService->delete($user);
            }
        } catch (Exception $e) {
            return new Response($e->getMessage(), 403, [
                "Content-Type" => "application/json"
            ]);
        }

        $response = new Response("L'utilisateur a bien été supprimé.", 200, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

}