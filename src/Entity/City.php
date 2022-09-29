<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=TravelSchedule::class, mappedBy="city")
     */
    private $travelSchedules;

    public function __construct()
    {
        $this->travelSchedules = new ArrayCollection();
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
            $travelSchedule->setCity($this);
        }

        return $this;
    }

    public function removeTravelSchedule(TravelSchedule $travelSchedule): self
    {
        if ($this->travelSchedules->removeElement($travelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($travelSchedule->getCity() === $this) {
                $travelSchedule->setCity(null);
            }
        }

        return $this;
    }
}
