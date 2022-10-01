<?php

namespace App\Entity;

use App\Repository\CartTicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartTicketRepository::class)
 */
class CartTicket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity=TravelSchedule::class, inversedBy="cartTickets")
     */
    private $travelSchedule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity = 1;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
