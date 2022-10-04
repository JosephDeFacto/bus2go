<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\Order;
use App\Form\OrderTypeFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OrderController extends AbstractController
{
    public ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/order-checkout", name="app_order")
     */
    public function index(Request $request, CartTicket $cartTicket): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $cartData = $this->managerRegistry->getRepository(CartTicket::class)->findAll();

        $fee = $cartData[0]->getTravelSchedule()->getFee();
        $user = $this->getUser();

        $order = new Order();

        $form = $this->createForm(OrderTypeFormType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($cartData as $data) {

                $order->setUser($user);
                $order->setStripeId(1);
                $order->setPrice($fee);
                $order->setQuantity(1);
                $order->setCartTicket($data);
            }



            $this->managerRegistry->getManager()->persist($order);

            $this->managerRegistry->getManager()->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('order/index.html.twig', [
            'orderForm' => $form->createView(),
        ]);
    }
}
