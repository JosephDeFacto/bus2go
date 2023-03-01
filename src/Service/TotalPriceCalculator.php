<?php

namespace App\Service;

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
        $childPrice = $this->formatNumber('float', $cart->getChildPrice());

        $studentPrice = $cart->getStudentPrice();
        $adultPrice = $cart->getAdultPrice();
        $pensionerPrice = $cart->getPensionerPrice();



        $cartQuantities = $this->cartTicketRepository->findOneBy(['user' => $user]);

        $totalPrice = ($cartQuantities->getChildQuantity() * $childPrice)
            + ($cartQuantities->getStudentQuantity() * $studentPrice)
            + ($cartQuantities->getAdultQuantity() * $adultPrice)
            + ($cartQuantities->getPensionerQuantity() * $pensionerPrice);


        return $this->formatNumber('float', $totalPrice);

    }

    public function formatNumber($type, $number): float
    {

        switch ($type) {
            case 'float':
                return number_format((float)$number, 2, '.', '');
            default:
                throw new \InvalidArgumentException(sprintf('Invalid format type "%s"', $type));
        }
    }
}