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
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Driver::class, mappedBy="busCompany")
     */
    private $drivers;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="busCompany")
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity=TravelSchedule::class, mappedBy="busCompany")
     */
    private $travelSchedules;

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
        $this->invoice = new ArrayCollection();
        $this->travelSchedules = new ArrayCollection();
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

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoice(): Collection
    {
        return $this->invoice;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoice->contains($invoice)) {
            $this->invoice[] = $invoice;
            $invoice->setBusCompany($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoice->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getBusCompany() === $this) {
                $invoice->setBusCompany(null);
            }
        }

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
            $travelSchedule->setBusCompany($this);
        }

        return $this;
    }

    public function removeTravelSchedule(TravelSchedule $travelSchedule): self
    {
        if ($this->travelSchedules->removeElement($travelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($travelSchedule->getBusCompany() === $this) {
                $travelSchedule->setBusCompany(null);
            }
        }

        return $this;
    }
}
