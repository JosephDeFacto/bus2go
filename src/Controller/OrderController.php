<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\TravelSchedule;
use App\Form\OrderTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="app_order")
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        // dohvati tikete iz carta i napravi order
        $cart = $entityManager->getRepository(Cart::class)->findAll();

        $travelSchedule = $entityManager->getRepository(TravelSchedule::class)->findAll();

        $travel = $this->getUser()->getCart()->getTravelSchedule();


        $order = new Order();

        $form = $this->createForm(OrderTypeFormType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setUser($this->getUser());
            $order->setStripeId(1);
            $order->setPrice($travel->getFee());
            $order->setQuantity(1);

            $entityManager->persist($order);

            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('order/index.html.twig', [
            'orderForm' => $form->createView(),
        ]);

    }
}
