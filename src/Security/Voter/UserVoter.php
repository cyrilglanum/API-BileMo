<?php

namespace App\Security\Voter;

use App\Entity\Users;
use App\Services\UserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    // ...

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'DELETE']) && $subject instanceof Users;
    }

    protected function voteOnAttribute($attribute, $user, $token): bool
    {
        // ROLE_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
    }

    public function voteForDelete($user, $attribute)
    {
        if (!$user instanceof Users) {
            throw new \Exception("L'authentification a échoué lors l'instance remontée.");
        }

        switch($attribute){
            case 'DELETE' :
                if($user->getRoles()[0] !== 'ROLE_ADMIN'){
                    throw new \Exception("Vous ne possedez pas les droits pour cette action.");
                }
        }

        // ... all the normal voter logic
    }
}