<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="meal",
 *     indexes={
 *          @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 */
class Meal
{
    public const NUM_ITEMS = 10;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="calories_number", type="integer", options={"unsigned"=true})
     */
    private $caloriesNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Many Meals have One User.
     *
     * @var User
     * @ManyToOne(targetEntity="User", inversedBy="meals")
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setCaloriesNumber(int $caloriesNumber): self
    {
        $this->caloriesNumber = $caloriesNumber;
        return $this;
    }

    public function getCaloriesNumber()
    {
        return $this->caloriesNumber;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
