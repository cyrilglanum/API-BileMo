<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ApiResource()
 * @ORM\Table()
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "article_by_id",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "all",
 *      href = @Hateoas\Route(
 *          "articles",
 *          absolute = true
 *      ),
 *      embedded = "jwt needed"
 * )
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Article extends abstractController
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     *
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     *
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     *
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     *
     */
    private $brand;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     *
     */
    private $capacity;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose
     *
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose
     *
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

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

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