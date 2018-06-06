<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="places",uniqueConstraints={@ORM\UniqueConstraint(name="places_name_unique",columns={"name"})})
 * @UniqueEntity("name")
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"price", "place","theme","test"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"price", "place","theme","test"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"price", "place","theme","test"})
     */
    protected $address;
    /**
     * @ORM\OneToMany(targetEntity="Price", mappedBy="place")
     * @Groups({"place"})
     * @var prices
     */
    protected $prices;
    /**
     * @ORM\OneToMany(targetEntity="Theme", mappedBy="place")
     * @var Theme[]
     */
    protected $themes;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Add price
     *
     * @param \AppBundle\Entity\Price $price
     *
     * @return Place
     */
    public function addPrice(\AppBundle\Entity\Price $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \AppBundle\Entity\Price $price
     */
    public function removePrice(\AppBundle\Entity\Price $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Add theme
     *
     * @param \AppBundle\Entity\Theme $theme
     *
     * @return Place
     */
    public function addTheme(\AppBundle\Entity\Theme $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme
     *
     * @param \AppBundle\Entity\Theme $theme
     */
    public function removeTheme(\AppBundle\Entity\Theme $theme)
    {
        $this->themes->removeElement($theme);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }
}
