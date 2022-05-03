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
        $mailExists = $this->findByMail($mail,$userRepository);

        if($mailExists != null){
            throw new \Exception("Cet email possède déjà un compte.");
        }

        return false;
    }

    public function add($user, UserRepository $userRepository): bool
    {
        $addUser = $userRepository->add($user);

        if($addUser !== true){
            throw new \Exception("L'utilisateur n'a pas pu être inséré.");
        }

        return true;
    }

    public function delete($user, UserRepository $userRepository): bool
    {
        $deleteUser = $userRepository->delete($user);

        if($deleteUser !== true){
            throw new \Exception("L'utilisateur n'a pas pu être supprimé.");
        }

        return true;
    }


    public function findByMail($mail, UserRepository $userRepository): ?\App\Entity\Users
    {
        $user = $userRepository->findOneBy(["email" => $mail]);

        if($user) {
            return $user;
        }else{
        	throw new \Exception("L'utilisateur n'a pas été trouvé.");
        }

    }
}