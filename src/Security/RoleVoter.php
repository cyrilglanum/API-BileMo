<?php namespace App\Security;

use App\Entity\Users;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleVoter extends Voter
{
    public const VIEW = 'view';
    public const READ = 'read';
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    public const ADD = 'add';

    private const ATTRIBUTES = [
        self::VIEW,
        self::READ,
        self::EDIT,
        self::DELETE,
        self::ADD,
    ];

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Users
            && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param string $a
     * @param Users $p
     * @param TokenInterface $t
     *
     * @return bool
     */
    protected function voteOnAttribute(
        $attribute,
        $project,
        TokenInterface $token
    )
    {
        switch ($attribute) {
            case self::ADD:
                return $this->canAdd($token->getUser());
            case self::DELETE:
                return $this->canDelete($token->getUser());
            case self::READ:
                return $this->canRead($token->getUser());
        }

        throw new \LogicException('Invalid attribute: ' . $attribute);
    }

    private function canAdd(?Users $user)
    {
        if (!$user) {
            return false;
        }

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

        if ($maxRole === 'admin' || $maxRole === 'client') {
            return true;
        }

        return false;
    }

    private function canDelete(?Users $user)
    {
        if (!$user) {
            return false;
        }

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

        if ($maxRole === 'admin' || $maxRole === 'client') {
            return true;
        }

        return false;
    }

    private function canRead(?Users $user)
    {
        if (!$user) {
            return false;
        }

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

        if ($maxRole === 'admin' || $maxRole === 'client') {
            return true;
        }

        return false;
    }



}