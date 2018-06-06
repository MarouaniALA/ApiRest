<?php
# src/AppBundle/Entity/Theme.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="themes",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="themes_name_place_unique", columns={"name", "place_id"})}
 * )
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"place","theme"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Choice({"art", "architecture", "history", "science-fiction", "sport"})
     * @Groups({"place","theme"})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer",message="La valeur {{ value }} est non de type {{ type }}.")
     * @Assert\GreaterThan(0)
     * @Assert\LessThanOrEqual(10)
     * @Groups({"place","theme"})
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="themes")
     * @Groups({"theme"})
     * @var Place
     */
    protected $place;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace(Place $place)
    {
        $this->place = $place;
    }
}
