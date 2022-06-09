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

    function mailExists($mail): bool
    {
        $mailExists = $this->findByMail($mail);

        if ($mailExists !== null && $mailExists !== false) {
            throw new \Exception("Cet email possède déjà un compte.");
        }

        return false;
    }

    function findUser($mail): bool
    {
        $user = $this->findByMail($mail);

        if ($user === false) {
            throw new \Exception("Le compte à supprimer n'existe pas.");
        }

        return false;
    }

    public function add($user): void
    {
        try {
            $this->userRepository->add($user);
        } catch (Exception $e) {
            throw new Exception("L'utilisateur n'a pas pu être inséré.");
        }
    }

    public function delete($user): void
    {
        try {
            $this->userRepository->delete($user);
        } catch (Exception $e) {
            throw new Exception("L'utilisateur n'a pas pu être supprimé.");
        }
    }

    public function findByMail($mail)
    {
        $user = $this->userRepository->findOneBy(["email" => $mail]);

        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function find($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception("L'utilisateur n'a pas pu être supprimé.");
        }else{
            return $user;
        }
    }
}