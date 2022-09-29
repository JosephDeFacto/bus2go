<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\TravelScheduleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(): Response
    {

        $user = $this->getUser();


        // cart can be null, fix this later
       /* if (!$user) {
            throw $this->createNotFoundException("User does not exists");
        }*/
        $tickets = $user->getCart()->getTravelSchedule();


        $ticketsCollection = [];

        $ticketsCollection['depart_from'] = $tickets->getDepartFrom();
        $ticketsCollection['travel_to'] = $tickets->getTravelTo();
        $ticketsCollection['depart_on'] = $tickets->getDepartingOn()->format('Y/m/d');
        $ticketsCollection['return_on'] = $tickets->getReturningOn()->format('Y/m/d');
        $ticketsCollection['departure_time'] = $tickets->getDepartureTime()->format('H:i');
        $ticketsCollection['time_arrival'] = $tickets->getTimeOfArrival()->format('H:i');
        $ticketsCollection['estimated_time'] = $tickets->getEstimatedArrivalTime()->format('H:i');
        $ticketsCollection['fee'] = $tickets->getFee();

        return $this->render('cart/index.html.twig', [
            'tickets' => $ticketsCollection,
        ]);
    }

    /**
     * @Route("/cart/add/{id}"), name="cart_addToCart")
     */
    public function addToCart(Request $request, TravelScheduleRepository $travelScheduleRepository, ManagerRegistry $registry): RedirectResponse
    {

        $manager = $registry->getManager();
        $id = $request->get('id');

        $cartUser = $this->getUser();

        $travelSchedule = $travelScheduleRepository->find($id);

        $cartTicket = new Cart();

        $cartTicket->setUser($cartUser);
        $cartTicket->setTravelSchedule($travelSchedule);

        $manager->persist($cartTicket);
        $manager->flush();

        $this->addFlash('success', 'Ticket added to cart');

        return $this->redirectToRoute('app_index');
    }

}
