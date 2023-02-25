<?php

namespace App\Entity;

use App\Repository\TravelScheduleRepository;
use App\Type\PassengerTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=TravelScheduleRepository::class)
 */
class TravelSchedule implements \JsonSerializable
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $departingOn;

    /**
     * @ORM\Column(type="date", nullable=true)
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
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="travelSchedules")
     */
    private $busDriver;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="travelSchedules")
     */
    private $city;


    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;


    /**
     * @ORM\OneToMany(targetEntity=DiscountTravelSchedule::class, mappedBy="travelSchedule")
     */
    private $discountTravelSchedules;

    /**
     * @ORM\Column(type="integer")
     */
    private $childPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $studentPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $adultPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $pensionerPrice;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="travelSchedule")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=BusCompany::class, inversedBy="travelSchedules", fetch="EAGER")
     */
    private $busCompany;

    public function __construct()
    {
        $this->discountTravelSchedules = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
            $discountTravelSchedule->setTravelSchedule($this);
        }

        return $this;
    }

    public function removeDiscountTravelSchedule(DiscountTravelSchedule $discountTravelSchedule): self
    {
        if ($this->discountTravelSchedules->removeElement($discountTravelSchedule)) {
            // set the owning side to null (unless already changed)
            if ($discountTravelSchedule->getTravelSchedule() === $this) {
                $discountTravelSchedule->setTravelSchedule(null);
            }
        }

        return $this;
    }

    public function getChildPrice(): ?int
    {
        return $this->childPrice;
    }

    public function setChildPrice(int $childPrice): self
    {
        $this->childPrice = $childPrice;

        return $this;
    }

    public function getStudentPrice(): ?int
    {
        return $this->studentPrice;
    }

    public function setStudentPrice(int $studentPrice): self
    {
        $this->studentPrice = $studentPrice;

        return $this;
    }

    public function getAdultPrice(): ?int
    {
        return $this->adultPrice;
    }

    public function setAdultPrice(int $adultPrice): self
    {
        $this->adultPrice = $adultPrice;

        return $this;
    }

    public function getPensionerPrice(): ?int
    {
        return $this->pensionerPrice;
    }

    public function setPensionerPrice(int $pensionerPrice): self
    {
        $this->pensionerPrice = $pensionerPrice;

        return $this;
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
            $order->setTravelSchedule($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getTravelSchedule() === $this) {
                $order->setTravelSchedule(null);
            }
        }

        return $this;
    }

    public function getBusCompany(): ?BusCompany
    {
        return $this->busCompany;
    }

    public function setBusCompany(?BusCompany $busCompany): self
    {
        $this->busCompany = $busCompany;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'departFrom' => $this->getDepartFrom(),
            'travelTo' => $this->getTravelTo(),
        ];
    }
}
