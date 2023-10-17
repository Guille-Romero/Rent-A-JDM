<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $chassis;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $engine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $horsepower;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Make::class, inversedBy="car")
     * @ORM\JoinColumn(nullable=false)
     */
    private $make;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="car")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getChassis(): ?string
    {
        return $this->chassis;
    }

    public function setChassis(?string $chassis): self
    {
        $this->chassis = $chassis;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function setEngine(string $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getHorsepower(): ?int
    {
        return $this->horsepower;
    }

    public function setHorsepower(?int $horsepower): self
    {
        $this->horsepower = $horsepower;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMake(): ?Make
    {
        return $this->make;
    }

    public function setMake(?Make $make): self
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addCar($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeCar($this);
        }

        return $this;
    }
}
