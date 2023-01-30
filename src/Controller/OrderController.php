<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CartTicketRepository;
use App\Repository\OrderRepository;
use App\Session\Session;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @TODO sort orders ASC
 */
class OrderController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct( ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/orders", name="order_index")
     */
    public function index(Session $cart, OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['user' => $this->getUser()]);

        return $this->render('order/index.html.twig', ['orders' => $orders]);

        /*foreach ($myOrders as $orders) {
            $orderData['departFrom'] = $orders->getCartTicket()->getTravelSchedule()->getDepartFrom();
            $orderData['travelTo'] = $orders->getCartTicket()->getTravelSchedule()->getTravelTo();
            $orderData['returnOn'] = $orders->getCartTicket()->getTravelSchedule()->getReturningOn();
            $orderData['departureTime'] = $orders->getCartTicket()->getTravelSchedule()->getDepartureTime();
            $orderData['timeOfArrival'] = $orders->getCartTicket()->getTravelSchedule()->getTimeOfArrival();
            $orderData['estArrivalTime'] = $orders->getCartTicket()->getTravelSchedule()->getEstimatedArrivalTime();
            $orderData['fee'] = $orders->getCartTicket()->getTravelSchedule()->getFee();
        }

        if ($orderData) {
            return $this->render('order/index.html.twig', ['orders' => $orderData]);
        }

        $this->addFlash('warning-orders', 'You have no orders yet!');
        return $this->render('order/index.html.twig', []);*/
    }

    /**
     * @Route("/order-checkout", name="app_order")
     * @param Request $request
     * @param CartTicketRepository $cartTicketRepository
     * @param OrderRepository $orderRepository
     * @return Response|null
     */
    public function orderCheckout(Request $request, Session $session): ?Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user= $this->getUser();

        $cart = $session->getSessionCart();

        $cartQuantities = $this->managerRegistry->getRepository(CartTicket::class)->findBy(['user' => $user]);

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        $order = new Order();
        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setUser($user);
            $order->setStripeId(1);
            $order->setPrice(100);
            $order->setReference($reference);
            $order->setTravelSchedule($cart);
            $this->managerRegistry->getManager()->persist($order);
            $this->managerRegistry->getManager()->flush();
            /**
             * @TODO do loop over an array of objects, then do deletion / or use findByOne() then delete without doing extra work with looping
             */
            $this->managerRegistry->getManager()->remove($cartQuantities[0]);
            $this->managerRegistry->getManager()->flush();


           $this->addFlash('success', 'Checkout completed.');
           $request->getSession()->clear('cart');
           return $this->redirectToRoute('app_index');

        }

        return $this->render('order/order-checkout.html.twig', [
            'orderForm' => $form->createView(),
            'reference' => $order->getReference(),
            'cart' => $cart,
            'user' => $user,
        ]);
    }
}
