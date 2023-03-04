<?php

namespace App\Service;

use App\Entity\TravelSchedule;
use App\Entity\User;
use App\Repository\CartTicketRepository;
use App\Session\Session;

class TotalPriceCalculator
{

    private CartTicketRepository $cartTicketRepository;

    public function __construct(CartTicketRepository $cartTicketRepository)
    {
        $this->cartTicketRepository = $cartTicketRepository;
    }

    public function calculateTotalPrice(Session $session, User $user): float
    {
        $cart = $session->getSessionCart();

        $childPrice = $cart->getChildPrice();
        $studentPrice = $cart->getStudentPrice();
        $adultPrice = $cart->getAdultPrice();
        $pensionerPrice = $cart->getPensionerPrice();

        $cartQuantities = $this->cartTicketRepository->findOneBy(['user' => $user]);

        return ($cartQuantities->getChildQuantity() * $childPrice)
            + ($cartQuantities->getStudentQuantity() * $studentPrice)
            + ($cartQuantities->getAdultQuantity() * $adultPrice)
            + ($cartQuantities->getPensionerQuantity() * $pensionerPrice);

    }
}