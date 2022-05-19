<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource()
 * @ORM\Table()
 * @method string getUserIdentifier()
 */
class Clients extends abstractController implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"client:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("client:read")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("client:read")
     */
    private $raisonsociale;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("client:read")
     */
    private $siret;

    /**
     * @ORM\Column(type="text")
     * @Groups("client:read")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Groups("client:read")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("client:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("client:read")
     */
    private $updatedAt;


    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return (string)$this->email;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getRaisonsociale()
    {
        return $this->raisonsociale;
    }

    public function setRaisonsociale($raisonsociale)
    {
        $this->raisonsociale = $raisonsociale;

        return $this;
    }

    public function getSiret()
    {
        return $this->siret;
    }

    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword():string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getIdentifier():string
    {
        return $this->email;
    }
}