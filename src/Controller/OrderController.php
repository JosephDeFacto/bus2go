<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CartTicketRepository;
use App\Repository\OrderRepository;
use App\Repository\TravelScheduleRepository;
use App\Service\TotalPriceCalculator;
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
    private Session $session;

    public function __construct( ManagerRegistry $managerRegistry, Session $session)
    {
        $this->managerRegistry = $managerRegistry;
        $this->session = $session;
    }

    /**
     * @Route("/orders", name="order_index")
     */
    public function index(Session $cart, OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['user' => $this->getUser()]);

        if (!$orders) {
            $this->addFlash('flash-warning', 'No orders yet');
            return $this->render('order/index.html.twig', []);
        }

        return $this->render('order/index.html.twig', ['orders' => $orders]);

    }

    /**
     * @Route("/order-checkout", name="app_order")
     * @param Request $request
     * @param TotalPriceCalculator $totalPriceCalculator
     * @return Response|null
     */
    public function orderCheckout(Request $request, TotalPriceCalculator $totalPriceCalculator): ?Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user= $this->getUser();

        $cart = $this->session->getSessionCart();
        $totalPrice = $totalPriceCalculator->calculateTotalPrice($this->session, $user);


        $cartQuantities = $this->managerRegistry->getRepository(CartTicket::class)->findOneBy(['user' => $user]);

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
            $order->setTotal($totalPrice);
            $this->managerRegistry->getManager()->persist($order);
            $this->managerRegistry->getManager()->flush();

            $this->managerRegistry->getManager()->remove($cartQuantities);
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
            'cartQuantities' => $cartQuantities,
        ]);
    }
}
