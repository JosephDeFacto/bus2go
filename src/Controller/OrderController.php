<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\Order;
use App\Form\OrderTypeFormType;
use App\Repository\CartTicketRepository;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OrderController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/orders", name="order_index")
     */
    public function index(): Response
    {
        // get my orders
        $myOrders = $this->managerRegistry->getRepository(Order::class)->findBy(['user' => $this->getUser()]);

        $orderData = [];
        foreach ($myOrders as $orders) {
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
        return $this->render('order/index.html.twig', []);
    }

    /**
     * @Route("/order-checkout", name="app_order")
     * @param Request $request
     * @param CartTicketRepository $cartTicketRepository
     * @param OrderRepository $orderRepository
     * @return Response|null
     */
    public function orderCheckout(Request $request, CartTicketRepository $cartTicketRepository, OrderRepository $orderRepository): ?Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cartData = $this->managerRegistry->getRepository(CartTicket::class)->findAll();

        $fee = $cartData[0]->getTravelSchedule()->getFee();
        $loggedUser = $this->getUser();
        /* $currentDay = date(strtotime('now'));
         $lastDayOfYear = strtotime('12/31');
         $numberOfDays = $lastDayOfYear - $currentDay;*/
        $userCart = $cartTicketRepository->findBy(['user' => $loggedUser]);

        $form = $this->createForm(OrderTypeFormType::class, null, [
            'user' => $this->getUser(),
        ]);

        $sessionData = $request->getSession()->get('session');
        $quantity = $sessionData->getQuantity();
        $user = $sessionData->getUser();
        $travel = $sessionData->getTravelSchedule();

        $form->handleRequest($request);
        $order = new Order();
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $reference = $date->format('dmY').'-'.uniqid();

            foreach ($cartData as $data) {
                $order->setUser($loggedUser);
                $order->setStripeId(1);
                $order->setPrice($fee);
                $order->setQuantity($quantity);
                $order->setCartTicket($data);
                $order->setReference($reference);
            }

            $this->managerRegistry->getManager()->persist($order);
            $this->managerRegistry->getManager()->flush();

            $clearSession = $request->getSession()->remove('session');
            return $this->redirectToRoute('app_invoice');
        }

        return $this->render('order/order-checkout.html.twig', [
            'orderForm' => $form->createView(),
            'reference' => $order->getReference(),
            'userCart' => $userCart,
            'user' => $user,
        ]);
    }
}
