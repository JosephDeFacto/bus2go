<?php

namespace App\Session;

use App\Entity\TravelSchedule;
use App\Repository\CartTicketRepository;
use App\Repository\TravelScheduleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Session
{
    private SessionInterface $session;
    private TravelScheduleRepository $travelScheduleRepository;
    private CartTicketRepository $cartTicketRepository;

    public function __construct(SessionInterface $session, TravelScheduleRepository $travelScheduleRepository, CartTicketRepository $cartTicketRepository)
    {
        $this->session = $session;
        $this->travelScheduleRepository = $travelScheduleRepository;
        $this->cartTicketRepository = $cartTicketRepository;
    }

    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function get(): ?array
    {
        return $this->session->get('cart') ?? [];
    }

    public function remove(): void
    {
        $this->session->remove('cart');
    }

    public function removeItem(int $id): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function getSessionCart(): ?TravelSchedule
    {

        $cart = $this->get() ?? [];

        $currentTicket = null;

        foreach ($cart as $id => $quantity) {

            $currentTicket = $this->travelScheduleRepository->find($id);

            if (!$currentTicket) {
                throw new NotFoundHttpException('No ticket found');
            }
        }
        return $currentTicket;
    }
}