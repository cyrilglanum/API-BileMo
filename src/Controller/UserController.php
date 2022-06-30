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
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class UserController extends abstractController
{
    /**
     * @Route("/api/v1/users/{id}", name="user_by_id")
     */
    public function getApiUser(UserRepository $userRepository, SerializerInterface $serializer, $id)
    {
        $user = $userRepository->find($id);

        if ($user === null) {
            return new Response("Aucun utilisateur trouvé avec cet identifiant.", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $json = $serializer->serialize($user, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/users/all", name="users")
     */
    public function getApiUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();

        if ($users === null) {
            return new Response("Aucun utilisateur trouvé.", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $json = $serializer->serialize($users, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/customer/{customer_id}/users", name="usersByCustomer")
     */
    public function getUsersLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id): Response
    {
        $customer = $clientRepository->find($customer_id);

        if ($customer === null) {
            return new Response("Le client n'existe pas.", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $customer_users = $userRepository->findBy(
            ['client_id' => $customer->getId()]
        );

        if ($customer_users === null) {
            return new Response("Aucun utilisateur relié à ce client n'a été trouvé.", 200, [
                "Content-Type" => "application/json"
            ]);
        }

        $json = $serializer->serialize($customer_users, 'json');

        $response = new Response($json, 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/customer/add/user", name="addUserToCustomer")
     */
    public function addUserLinkedToCustomer(Request $request, UserService $userService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('add', $this->getUser());

        $role = $this->checkRole($this->getUser());

        $user_infos = json_decode($request->request->get('user'));

        if ($role === 'client') {
            if ($this->getUser()->getClientId() !== json_decode($request->request->get('client_id'))) {
                return new Response("Vous n'êtes pas autorisé à effectuer cette action.", 401, ["Content-Type" => "application/json"]);
            }
        }

        $user = new Users();

        $user->setLastname(htmlentities($user_infos->lastname));
        $user->setFirstname(htmlentities($user_infos->firstname));
        $user->setEmail(htmlentities($user_infos->email));
        $user->setPostalcode($user_infos->postal_code);
        $user->setVille(htmlentities($user_infos->ville));
        $user->setActif($user_infos->actif);
        $user->setRoles([str_replace('\'', "\"", $user_roles->roles ?? "ROLE_USER")]);
        $user->setClientId(json_decode($request->request->get('client_id')));
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setUpdatedAt(new \DateTimeImmutable('now'));
        $user->setPassword($userPasswordHasher->hashPassword($user, $user_infos->password));

        try {
            $emailExists = $userService->mailExists($user_infos->email);

            if ($emailExists === false) {
                $userService->add($user);
            }
        } catch (Exception $e) {
            return new Response($e->getMessage(), 403, [
                "Content-Type" => "application/json"
            ]);
        }

        $response = new Response("L'utilisateur a bien été ajouté.", 200, [
            "Content-Type" => "application/json"
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Route("/api/v1/customer/delete/user", name="deleteUser")
     */
    public
    function deleteUserLinkedToCustomer(Request $request, UserService $userService): Response
    {
        $this->denyAccessUnlessGranted('delete', $this->getUser());

        $userToDelete = $userService->find(json_decode($request->request->get('id')));

        $role = $this->checkRole($this->getUser());

        if ($role === 'client') {
            if ($this->getUser()->getClientId() !== json_decode($request->request->get('client_id'))) {
                return new Response("Vous n'êtes pas autorisé à effectuer cette action.", 401, ["Content-Type" => "application/json"]);
            }
        }

        if ($userToDelete) {
            try {
                $userService->delete($userToDelete);
            } catch (Exception $e) {
                return new Response($e->getMessage(), 403, [
                    "Content-Type" => "application/json"
                ]);
            }

            $response = new Response("L'utilisateur a bien été supprimé.", 200, [
                "Content-Type" => "application/json"
            ]);
        }

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