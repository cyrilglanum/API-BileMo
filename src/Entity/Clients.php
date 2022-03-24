<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ApiResource()
 * @ORM\Table()
 */
class Clients
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $raisonsociale;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $siret;

    /**
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function getId()
    {
        return $this->id;
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
    public function getPassword()
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
}