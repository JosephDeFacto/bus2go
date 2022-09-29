<?php

namespace App\Entity;

use App\Repository\BusCompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BusCompanyRepository::class)
 */
class BusCompany
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $availability;

    /**
     * @ORM\OneToMany(targetEntity=Bus::class, mappedBy="busCompany")
     */
    private $buses;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Driver::class, mappedBy="busCompany")
     */
    private $drivers;

    public function __construct()
    {
        $this->buses = new ArrayCollection();
        $this->drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Collection<int, Bus>
     */
    public function getBuses(): Collection
    {
        return $this->buses;
    }

    public function addBus(Bus $bus): self
    {
        if (!$this->buses->contains($bus)) {
            $this->buses[] = $bus;
            $bus->setBusCompany($this);
        }

        return $this;
    }

    public function removeBus(Bus $bus): self
    {
        if ($this->buses->removeElement($bus)) {
            // set the owning side to null (unless already changed)
            if ($bus->getBusCompany() === $this) {
                $bus->setBusCompany(null);
            }
        }

        return $this;
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
            $driver->setBusCompany($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        if ($this->drivers->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getBusCompany() === $this) {
                $driver->setBusCompany(null);
            }
        }

        return $this;
    }
}
