<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *          value ="today",
     *          message = "La date de début doit être supérieure à la date du jour"            
     * )
     * @Assert\Type("\DateTimeInterface")
     */
    private $startDateTime;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 5,
     *      max = 10080,
     *      notInRangeMessage = "La durée doit être comprise entre {{ min }} minutes et {{ max }} minutes (= 1 semaine)"
     * )
     */
    private $duration;


    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *          value ="today",
     *          message = "La date d'inscription doit être supérieure à la date du jour" 
     * )
     * @Assert\Type("\DateTimeInterface")
     * @Assert\LessThan(
     *          propertyPath="startDateTime",
     *          message = "La date d'inscription doit être inférieure à la date de début de sortie"
     * )
     */
    private $endRegisterDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 2,
     *      max = 999,
     *      notInRangeMessage = "Le nombre de participants doit être compris entre {{ min }} et {{ max }}",
     * )
     */
    private $nbParticipantMax;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum"
     * )
     */
    private $details;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     *         message = "Veuillez choisir un lieu"
     * )
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Ignore()
     * @Assert\NotBlank
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="eventRegistrations")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

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

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganizer(): ?Participant
    {
        return $this->organizer;
    }

    public function setOrganizer(?Participant $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
