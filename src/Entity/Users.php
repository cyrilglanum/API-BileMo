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
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "user_by_id",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "all",
 *      href = @Hateoas\Route(
 *          "users",
 *          absolute = true
 *      ),
 *      embedded = "jwt needed"
 * )
 * @method string getUserIdentifier()
 */
class Users extends abstractController implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string",
     *length=100)     * @Serializer\Expose()
     */
    private $lastname;

    /**
     * @ORM\Column(type="string",
     *length=100)     * @Serializer\Expose()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $postalcode;

    /**
     * @ORM\Column(type="string",
     *length=100)     * @Serializer\Expose()
     */
    private $ville;

    /**
     * @ORM\Column(type="integer",
     *length=100)     * @Serializer\Expose()
     */
    private $actif;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $client_id;

    /**
     * @ORM\Column(type="string",
     *length=100)     * @Serializer\Expose()
     */
    private $token;

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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Expose()
     */
    private $roles;


    public function getId()
    {
        return $this->id;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

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

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    public function getPostalcode()
    {
        return $this->postalcode;
    }

    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    public function getActif()
    {
        return $this->actif;
    }

    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function setCreatedAt($created_at)
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updatedAt = $updated_at;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
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
}