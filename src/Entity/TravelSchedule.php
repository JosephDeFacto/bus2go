<?php

namespace App\Entity;

use App\Repository\TravelScheduleRepository;
use App\Type\PassengerTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\InvalidArgumentException;

/**
 * @ORM\Entity(repositoryClass=TravelScheduleRepository::class)
 */
class TravelSchedule
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $departFrom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $travelTo;

    /**
     * @ORM\Column(type="date")
     */
    private $departingOn;

    /**
     * @ORM\Column(type="date")
     */
    private $returningOn;

    /**
     * @ORM\Column(type="time")
     */
    private $departureTime;

    /**
     * @ORM\Column(type="time")
     */
    private $timeOfArrival;

    /**
     * @ORM\Column(type="time")
     */
    private $estimatedArrivalTime;

    /**
     * @ORM\Column(type="float")
     */
    private $fee;

    /**
     * @ORM\ManyToOne(targetEntity=Bus::class, inversedBy="travelSchedules")
     */
    private $bus;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="travelSchedules")
     */
    private $busDriver;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="travelSchedules")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="travelSchedule")
     */
    private $carts;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $passenger;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartFrom(): ?string
    {
        return $this->departFrom;
    }

    public function setDepartFrom(string $departFrom): self
    {
        $this->departFrom = $departFrom;

        return $this;
    }

    public function getTravelTo(): ?string
    {
        return $this->travelTo;
    }

    public function setTravelTo(string $travelTo): self
    {
        $this->travelTo = $travelTo;

        return $this;
    }

    public function getDepartingOn()
    {
        return $this->departingOn;
    }

    public function setDepartingOn(\DateTimeInterface $departingOn): self
    {
        /*$this->departingOn = $departingOn->format('Y-m-d H:i:s');*/
        $this->departingOn = $departingOn->format('Y-m-d');

        return $this;
    }

    public function getReturningOn()
    {
        return $this->returningOn;
    }

    public function setReturningOn(\DateTimeInterface $returningOn): self
    {
       /* $this->returningOn = $returningOn->format('Y-m-d H:i:s');*/
        $this->returningOn = $returningOn->format('Y-m-d');

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getTimeOfArrival(): ?\DateTimeInterface
    {
        return $this->timeOfArrival;
    }

    public function setTimeOfArrival(\DateTimeInterface $timeOfArrival): self
    {
        $this->timeOfArrival = $timeOfArrival;

        return $this;
    }

    public function getEstimatedArrivalTime(): ?\DateTimeInterface
    {
        return $this->estimatedArrivalTime;
    }

    public function setEstimatedArrivalTime(\DateTimeInterface $estimatedArrivalTime): self
    {
        $this->estimatedArrivalTime = $estimatedArrivalTime;

        return $this;
    }

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function setFee(float $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(?Bus $bus): self
    {
        $this->bus = $bus;

        return $this;
    }

    public function getBusDriver(): ?Driver
    {
        return $this->busDriver;
    }

    public function setBusDriver(?Driver $busDriver): self
    {
        $this->busDriver = $busDriver;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setTravelSchedule($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getTravelSchedule() === $this) {
                $cart->setTravelSchedule(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPassenger(): ?string
    {
        return $this->passenger;
    }

    public function setPassenger(string $passenger): self
    {

        if (!in_array($passenger, PassengerTypeEnum::getAvailableTypes())) {
            throw new InvalidArgumentException('Invalid type');
        }

        $this->passenger = $passenger;

        return $this;
    }
}
