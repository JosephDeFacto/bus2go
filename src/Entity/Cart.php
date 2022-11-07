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
     * @ORM\OneToMany(targetEntity=CartTicket::class, mappedBy="cart")
     */
    private $cartTickets;

    public function __construct()
    {
        $this->cartTickets = new ArrayCollection();
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

    /**
     * @return Collection<int, CartTicket>
     */
    public function getCartTickets(): Collection
    {
        return $this->cartTickets;
    }

    public function addCartTicket(CartTicket $cartTicket): self
    {
        if (!$this->cartTickets->contains($cartTicket)) {
            $this->cartTickets[] = $cartTicket;
            $cartTicket->setCart($this);
        }

        return $this;
    }

    public function removeCartTicket(CartTicket $cartTicket): self
    {
        if ($this->cartTickets->removeElement($cartTicket)) {
            // set the owning side to null (unless already changed)
            if ($cartTicket->getCart() === $this) {
                $cartTicket->setCart(null);
            }
        }

        return $this;
    }
}
