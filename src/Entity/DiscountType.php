<?php

namespace App\Entity;

use App\Repository\DiscountTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountTypeRepository::class)
 */
class DiscountType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity=DiscountTravelSchedule::class, mappedBy="discount")
     */
    private $discountTravelSchedules;

    public function __construct()
    {
        $this->discountTravelSchedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getUnit(): ?array
    {
        return $this->unit;
    }

    public function setUnit(?array $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, DiscountTravelSchedule>
     */
    public function getDiscountTravelSchedules(): Collection
    {
        return $this->discountTravelSchedules;
    }

    public function addDiscountTravelSchedule(DiscountTravelSchedule $discountTravelSchedule): self
    {
        if (!$this->discountTravelSchedules->contains($discountTravelSchedule)) {
            $this->discountTravelSchedules[] = $discountTravelSchedule;
            $discountTravelSchedule->setDiscount($this);
        }

        return $this;
    }

    public function removeDiscountTravelSchedule(DiscountTravelSchedule $discountTravelSchedule): self
    {
        if ($this->discountTravelSchedules->removeElement($discountTravelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($discountTravelSchedule->getDiscount() === $this) {
                $discountTravelSchedule->setDiscount(null);
            }
        }

        return $this;
    }
}
