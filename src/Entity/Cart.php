<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cart", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=TravelSchedule::class, inversedBy="carts", fetch="EAGER")
     * @ORM\JoinColumn(name="travel_schedule_id", referencedColumnName="id")
     */
    private $travelSchedule;


    public function __construct()
    {
        $this->travelSchedule = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
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

}
