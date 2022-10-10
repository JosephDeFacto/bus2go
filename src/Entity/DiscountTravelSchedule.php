<?php

namespace App\Entity;

use App\Repository\DiscountTravelScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountTravelScheduleRepository::class)
 */
class DiscountTravelSchedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TravelSchedule::class, inversedBy="discountTravelSchedules")
     */
    private $travelSchedule;

    /**
     * @ORM\ManyToOne(targetEntity=DiscountType::class, inversedBy="discountTravelSchedules")
     */
    private $discount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelSchedule(): ?TravelSchedule
    {
        return $this->travelSchedule;
    }

    public function setTravelSchedule(?TravelSchedule $travelSchedule): self
    {
        $this->travelSchedule = $travelSchedule;

        return $this;
    }

    public function getDiscount(): ?DiscountType
    {
        return $this->discount;
    }

    public function setDiscount(?DiscountType $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}
