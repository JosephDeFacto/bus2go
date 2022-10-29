<?php

namespace App\Entity;

use App\Repository\CartTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
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

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="cartTicket", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $orders;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

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

    public function __toString(): string
    {
        return $this->getUser();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCartTicket($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCartTicket() === $this) {
                $order->setCartTicket(null);
            }
        }

        return $this;
    }
}
