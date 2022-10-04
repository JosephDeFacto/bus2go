<?php

namespace App\Entity;

use App\Repository\PassengerTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PassengerTypeRepository::class)
 */
class PassengerType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=CartTicket::class, mappedBy="passengerType")
     */
    private $cartTickets;

    public function __construct()
    {
        $this->cartTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $cartTicket->setPassengerType($this);
        }

        return $this;
    }

    public function removeCartTicket(CartTicket $cartTicket): self
    {
        if ($this->cartTickets->removeElement($cartTicket)) {
            // set the owning side to null (unless already changed)
            if ($cartTicket->getPassengerType() === $this) {
                $cartTicket->setPassengerType(null);
            }
        }

        return $this;
    }
}
