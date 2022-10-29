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
     * @Route("/order-checkout", name="app_order")
     * @param Request $request
     * @param CartTicketRepository $cartTicketRepository
     * @param OrderRepository $orderRepository
     * @return Response|null
     */
    public function index(Request $request, CartTicketRepository $cartTicketRepository, OrderRepository $orderRepository): ?Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cartData = $this->managerRegistry->getRepository(CartTicket::class)->findAll();

        $fee = $cartData[0]->getTravelSchedule()->getFee();
        $user = $this->getUser();
       /* $currentDay = date(strtotime('now'));
        $lastDayOfYear = strtotime('12/31');
        $numberOfDays = $lastDayOfYear - $currentDay;*/
        $userCart = $cartTicketRepository->findBy(['user' => $user]);

        $form = $this->createForm(OrderTypeFormType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        $order = new Order();
        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $reference = $date->format('dmY').'-'.uniqid();

            foreach ($cartData as $data) {

                $order->setUser($user);
                $order->setStripeId(1);
                $order->setPrice($fee);
                $order->setQuantity($data->getQuantity());
                $order->setCartTicket($data);
                $order->setReference($reference);
            }

            $orders = $orderRepository->findOneBy(['user' => $user]);

            $this->managerRegistry->getManager()->persist($order);
            $this->managerRegistry->getManager()->flush();

            return $this->redirectToRoute('app_invoice');

        }

        return $this->render('order/index.html.twig', [
            'orderForm' => $form->createView(),
            'reference' => $order->getReference(),
            'userCart' => $userCart,
            'user' => $user,
        ]);
    }
}
