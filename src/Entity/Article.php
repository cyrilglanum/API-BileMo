<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository;
use Hateoas\Configuration\Annotation as Hateoas;



/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ApiResource()
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "article_by_id",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @ORM\Table()
 */
class Article extends abstractController
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("article:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("article:read")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $brand;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("article:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("article:read")
     */
    private $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

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