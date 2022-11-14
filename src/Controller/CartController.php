<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Form\CartType;
use App\Repository\CartTicketRepository;
use App\Repository\TravelScheduleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CartController extends AbstractController
{
    public ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/cart", name="cart_index")
     * @ParamConverter("cartTicket")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $tickets = $this->managerRegistry->getRepository(CartTicket::class)->findBy(['user' => $user]);

        return $this->render('cart/index.html.twig', ['cartTickets' => $tickets]);
    }

    /**
     * @Route("/cart/add/{id}"), name="cart_addToCart")
     */
    public function addToCart(Request $request, TravelScheduleRepository $travelScheduleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $id = $request->get('id');

        $user = $this->getUser();
        $cartTicket = new CartTicket();

        $travelSchedule = $travelScheduleRepository->find($id);

        $form = $this->createForm(CartType::class, $cartTicket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setUser($user);
            $cartTicket->setTravelSchedule($travelSchedule);
           /* $cartTicket->setQuantity($cartTicket->getQuantity());*/
            $cartTicket->setChildQuantity($form->getData()->getChildQuantity());
            $cartTicket->setStudentQuantity($form->getData()->getStudentQuantity());
            $cartTicket->setAdultQuantity($form->getData()->getAdultQuantity());
            $cartTicket->setPensionerQuantity($form->getData()->getPensionerQuantity());
            $session = $request->getSession()->set('session', $cartTicket);

            $this->managerRegistry->getManager()->persist($cartTicket);
            $this->managerRegistry->getManager()->flush();

            $this->addFlash('success', 'Ticket added to cart');

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/add.html.twig', ['form' => $form->createView(), 'cartTicket' => $cartTicket, 'travelSchedule' => $travelSchedule]);
    }

    /**
     * @Route("cart/update/{id}", name="cart_updateToCart")
     */
    public function updateCart(ManagerRegistry $registry, Request $request, CartTicketRepository $cartTicketRepository, CartTicket $cartTicket)
    {
        $id = $request->get('id');

        $cart = $cartTicketRepository->find($id);

        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setChildQuantity($form->getData()->getChildQuantity());
            $cartTicket->setStudentQuantity($form->getData()->getStudentQuantity());
            $cartTicket->setAdultQuantity($form->getData()->getAdultQuantity());
            $cartTicket->setPensionerQuantity($form->getData()->getPensionerQuantity());
            $entityManager = $registry->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }
        return $this->render('cart/update.html.twig', ['cartTicket' => $cart, 'form' => $form->createView()]);
    }

    /**
     * @Route("cart/delete/{id}", name="cart_deleteToCart")
     */
    public function deleteFromCart(Request $request)
    {
        $id = $request->get('id');

        $cartId = $this->managerRegistry->getRepository(CartTicket::class)->find($id);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($cartId);
        $entityManager->flush();
        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("cart/clear", name="cart_clear")
     */
    public function clearCart(ManagerRegistry $registry): Response
    {
        $entityManager = $registry->getManager();

        $cartTickets = $entityManager->getRepository(CartTicket::class)->findAll();

        foreach ($cartTickets as $tickets) {
            $entityManager->remove($tickets);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_index');

    }
}