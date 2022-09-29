<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 */
class Driver
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer")
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity=TravelSchedule::class, mappedBy="busDriver")
     */
    private $travelSchedules;

    /**
     * @ORM\ManyToOne(targetEntity=BusCompany::class, inversedBy="drivers")
     */
    private $busCompany;

    /**
     * @ORM\ManyToOne(targetEntity=Bus::class, inversedBy="drivers")
     */
    private $bus;

    public function __construct()
    {
        $this->travelSchedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getContact(): ?int
    {
        return $this->contact;
    }

    public function setContact(int $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, TravelSchedule>
     */
    public function getTravelSchedules(): Collection
    {
        return $this->travelSchedules;
    }

    public function addTravelSchedule(TravelSchedule $travelSchedule): self
    {
        if (!$this->travelSchedules->contains($travelSchedule)) {
            $this->travelSchedules[] = $travelSchedule;
            $travelSchedule->setBusDriver($this);
        }

        return $this;
    }

    public function removeTravelSchedule(TravelSchedule $travelSchedule): self
    {
        if ($this->travelSchedules->removeElement($travelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($travelSchedule->getBusDriver() === $this) {
                $travelSchedule->setBusDriver(null);
            }
        }

        return $this;
    }

    public function getBusCompany(): ?BusCompany
    {
        return $this->busCompany;
    }

    public function setBusCompany(?BusCompany $busCompany): self
    {
        $this->busCompany = $busCompany;

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(?Bus $bus): self
    {
        $this->bus = $bus;

        return $this;
    }
}
