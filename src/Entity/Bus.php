<?php

namespace App\Entity;

use App\Repository\BusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BusRepository::class)
 */
class Bus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $busNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $fareNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\ManyToOne(targetEntity=BusCompany::class, inversedBy="buses")
     */
    private $busCompany;

    /**
     * @ORM\OneToMany(targetEntity=TravelSchedule::class, mappedBy="bus")
     */
    private $travelSchedules;

    /**
     * @ORM\OneToMany(targetEntity=Driver::class, mappedBy="bus")
     */
    private $drivers;

    public function __construct()
    {
        $this->travelSchedules = new ArrayCollection();
        $this->drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusNumber(): ?int
    {
        return $this->busNumber;
    }

    public function setBusNumber(int $busNumber): self
    {
        $this->busNumber = $busNumber;

        return $this;
    }

    public function getFareNumber(): ?string
    {
        return $this->fareNumber;
    }

    public function setFareNumber(string $fareNumber): self
    {
        $this->fareNumber = $fareNumber;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

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
            $travelSchedule->setBus($this);
        }

        return $this;
    }

    public function removeTravelSchedule(TravelSchedule $travelSchedule): self
    {
        if ($this->travelSchedules->removeElement($travelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($travelSchedule->getBus() === $this) {
                $travelSchedule->setBus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Driver>
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers[] = $driver;
            $driver->setBus($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        if ($this->drivers->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getBus() === $this) {
                $driver->setBus(null);
            }
        }

        return $this;
    }
}
