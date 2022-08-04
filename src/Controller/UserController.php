<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;

class UserController extends abstractController
{
    /**
     * @Route("/api/v1/users/{id}", name="userById", methods={"GET"})
     */
    public function getApiUser(UserRepository $userRepository, SerializerInterface $serializer, $id)
    {
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les utilisateurs.", 403, ["Content-Type" => "application/json"]);
        }

        $role = $this->checkRole($this->getUser());

        if ($role === 'admin') {
            $user = $userRepository->find($id);
        } else {
            $user = $userRepository->find($id);

            if ($user == null) {
                return new Response("Aucun utilisateur trouvé avec cet identifiant.", 404, [
                    "Content-Type" => "application/json"
                ]);
            }
            if ($user->getClientId() != $this->getUser()->getClientId()) {
                return new Response("Vous n'êtes pas autorisé à voir cet utilisateur.", 403, ["Content-Type" => "application/json"]);
            }
        }

        if ($user == null) {
            return new Response("Aucun utilisateur trouvé avec cet identifiant.", 404, [
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
     * @Route("/api/v1/users", name="users", methods={"GET"})
     */
    public function getApiUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les utilisateurs.", 403, ["Content-Type" => "application/json"]);
        }

        $role = $this->checkRole($this->getUser());

        if ($role === 'admin') {
            $users = $userRepository->findAll();
        } else {
            $users = $userRepository->findBy(['client_id' => $this->getUser()->getClientId()]);
        }

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
     * @Route("/api/v1/users", name="addUserLinkedToCustomer", methods={"POST"})
     */
    public function addUserLinkedToCustomer(Request $request, UserService $userService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        try {
            $this->denyAccessUnlessGranted('add', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à effectuer cette action.", 401, ["Content-Type" => "application/json"]);
        }

        $role = $this->checkRole($this->getUser());

        $user_infos = json_decode($request->get('user'));
        $user_roles = json_decode($request->request->get('roles'));
        $client_id = json_decode($request->request->get('client_id'));

        if ($role === 'client') {
            if ($this->getUser()->getClientId() !== $client_id) {
                return new Response("Vous n'êtes pas autorisé à effectuer cette action.", 403, ["Content-Type" => "application/json"]);
            }
        }

        $user = new Users();

        $user->setLastname(htmlentities($user_infos->lastname));
        $user->setFirstname(htmlentities($user_infos->firstname));
        $user->setEmail(htmlentities($user_infos->email));

        if (!(is_int($user_infos->postal_code) && is_int($user_infos->actif) && is_int($client_id))) {
            return new Response("Données incorrectes.", 401, ["Content-Type" => "application/json"]);
        }
        $user->setPostalcode($user_infos->postal_code);
        $user->setVille(htmlentities($user_infos->ville));
        $user->setActif($user_infos->actif);
        $user->setRoles([str_replace('\'', "\"", $user_roles->roles ?? "ROLE_USER")]);
        $user->setClientId($client_id);
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
     * @Route("/api/v1/users/{id}", name="deleteUser", methods={"DELETE"})
     */
    public function deleteUserLinkedToCustomer(Request $request, UserService $userService): Response
    {
        try {
            $this->denyAccessUnlessGranted('delete', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à effectuer cette action.", 403, ["Content-Type" => "application/json"]);
        }

        if (!is_int((int)$request->get('id'))) {
            return new Response("Données incorrectes.", 401, ["Content-Type" => "application/json"]);
        }

        $userToDelete = $userService->find((int)$request->get('id'));

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

    /**
     * @Route("/api/v1/customers/{customer_id}/users", name="usersByCustomer", methods={"GET"})
     */
    public function getUsersLinkedToCustomer(UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, $customer_id): Response
    {
        try {
            $this->denyAccessUnlessGranted('read', $this->getUser());
        } catch (Exception $e) {
            return new Response("Vous n'êtes pas autorisé à voir les utilisateurs.", 403, ["Content-Type" => "application/json"]);
        }

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