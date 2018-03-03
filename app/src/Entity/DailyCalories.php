<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="daily_calories",
 *     indexes={
 *          @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DailyCaloriesRepository")
 */
class DailyCalories
{
    public const DEFAULT_DAILY_CALORIES_NUMBER = 4000;

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
     * @var int
     *
     * @ORM\Column(name="daily_calories_number", type="integer")
     */
    private $dailyCaloriesNumber = self::DEFAULT_DAILY_CALORIES_NUMBER;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDailyCaloriesNumber(int $caloriesNumber): self
    {
        $this->dailyCaloriesNumber = $caloriesNumber;
        return $this;
    }

    public function getDailyCaloriesNumber()
    {
        return $this->dailyCaloriesNumber;
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
}
