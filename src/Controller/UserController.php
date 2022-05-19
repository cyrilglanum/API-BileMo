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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        $user = $userRepository->find($id);

        $json = $serializer->serialize($user, 'json', ['groups' => 'user:read']);

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

//    /**
//     * @Route("/api/v1/customer/{customer_id}/user/{id}", name="userFromCustomer")
//     */
//    public function getUserLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id, $id): Response
//    {
//        $customer = $clientRepository->find($customer_id);
//
//        $customer_user = $userRepository->findOneBy([
//                'client_id' => $customer->getId(),
//                'id' => $id]
//        );
//
//        $json = $serializer->serialize($customer_user, 'json', ['groups' => 'user:read']);
//
//        $response = new Response($json, 200, [
//            "Content-Type" => "application/json"
//        ]);
//
//        return $response;
//    }

    /**
     * @Route("/api/v1/customer/add/user", name="addUserToCustomer")
     */
    public function addUserLinkedToCustomer(Request $request, UserService $userService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user_infos = json_decode($request->request->get('user'));
        $user_roles= json_decode($request->request->get('roles'));

        $user = new Users();

        $user->setLastname($user_infos->lastname);
        $user->setFirstname($user_infos->firstname);
        $user->setEmail($user_infos->email);
        $user->setPostalcode($user_infos->postal_code);
        $user->setVille($user_infos->ville);
        $user->setActif($user_infos->actif);
        $user->setRoles([str_replace('\'', "\"",$user_roles->roles ?? "ROLE_USER")] ) ;
        $user->setClientId(json_decode($request->request->get('customer_id')));
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setUpdatedAt(new \DateTimeImmutable('now'));
        $user->setPassword($userPasswordHasher->hashPassword($user, $user_infos->password));

        try {
            $emailExists = $userService->mailExists($user_infos->email);

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
     * @Route("/api/v1/customer/delete/user/{id}", name="deleteUser")
     */
    public function deleteUserLinkedToCustomer(UserService $userService, $id): Response
    {
        try {
            $user = $userService->find($id);

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