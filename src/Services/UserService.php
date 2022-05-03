<?php

namespace App\Services;

use App\Repository\UserRepository;
use Exception;

class UserService
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    function mailExists($mail, UserRepository $userRepository): bool
    {
        $mailExists = $this->findByMail($mail, $userRepository);

        if ($mailExists !== null && $mailExists !== false) {
            throw new \Exception("Cet email possède déjà un compte.");
        }

        return false;
    }

    function findUser($mail, UserRepository $userRepository): bool
    {
        $user = $this->findByMail($mail, $userRepository);

        if ($user === false) {
            throw new \Exception("Le compte à supprimer n'existe pas.");
        }

        return false;
    }

    public function add($user, UserRepository $userRepository): bool
    {
        $addUser = $userRepository->add($user);

        if ($addUser !== true) {
            throw new \Exception("L'utilisateur n'a pas pu être inséré.");
        }

        return true;
    }

    public function delete($user, UserRepository $userRepository): bool
    {
        $deleteUser = $userRepository->delete($user);

        if ($deleteUser !== true) {
            throw new \Exception("L'utilisateur n'a pas pu être supprimé.");
        }

        return true;
    }

    public function findByMail($mail, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(["email" => $mail]);

        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
}