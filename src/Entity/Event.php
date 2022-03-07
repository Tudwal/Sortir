<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDateTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="date")
     */
    private $endRegisterDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbParticipantMax;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $details;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getEndRegisterDate(): ?\DateTimeInterface
    {
        return $this->endRegisterDate;
    }

    public function setEndRegisterDate(\DateTimeInterface $endRegisterDate): self
    {
        $this->endRegisterDate = $endRegisterDate;

        return $this;
    }

    public function getNbParticipantMax(): ?int
    {
        return $this->nbParticipantMax;
    }

    public function setNbParticipantMax(int $nbParticipantMax): self
    {
        $this->nbParticipantMax = $nbParticipantMax;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }
}
