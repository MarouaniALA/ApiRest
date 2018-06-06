<?php
# src/AppBundle/Entity/Preference.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="preferences",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="preferences_name_user_unique", columns={"name", "user_id"})}
 * )
 */
class Preference
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"user", "preference"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Choice({"art", "architecture", "history", "science-fiction", "sport"})
     * @Groups({"user", "preference"})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer",message="La valeur {{ value }} est non de type {{ type }}.")
     * @Assert\GreaterThan(0)
     * @Assert\LessThanOrEqual(10)
     * @Groups({"user", "preference"})
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="preferences")
     * @var User
     * @Groups({"preference"})
     */
    protected $user;

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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
    public function match(Theme $theme)
    {
        return $this->name === $theme->getName();
    }
}
