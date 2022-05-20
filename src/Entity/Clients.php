<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @ApiResource()
 * @ORM\Table()
 *  @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "client_by_id",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "all",
 *      href = @Hateoas\Route(
 *          "clients",
 *          absolute = true
 *      ),
 *      embedded = "jwt needed"
 * )
 *
 * @method string getUserIdentifier()
 */
class Clients extends abstractController implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose()
     */
    private $raisonsociale;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose()
     */
    private $siret;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
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