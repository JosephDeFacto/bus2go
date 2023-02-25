<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Form\CartType;
use App\Repository\CartTicketRepository;
use App\Repository\TravelScheduleRepository;
use App\Session\Session;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Dto\DashboardDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use function Symfony\Component\String\b;

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
     * @param Session $cart
     * @return Response
     */
    public function index(Session $cart): Response
    {
        $user = $this->getUser();

        $cartTicketQuantity = $this->managerRegistry->getRepository(CartTicket::class)->findBy(['user' => $user]);
        $cartTicket = $cart->getSessionCart();

        if (!$cart->getSessionCart()) {
            $this->addFlash('warning-cart', '');
            return $this->render('cart/index.html.twig', []);
        }

        return $this->render('cart/index.html.twig', ['cartTicket' => $cartTicket, 'ticketQuantity' => $cartTicketQuantity]);
    }

    /**
     * @Route("/cart/add/{id}"), name="cart_addToCart")
     */
    public function addToCart(Session $cart, int $id, Request $request, TravelScheduleRepository $travelScheduleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $cartTicket = new CartTicket();

        $travelSchedule = $travelScheduleRepository->find($id);

        $busCompany = $travelSchedule->getBusCompany();


        $form = $this->createForm(CartType::class, $cartTicket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setUser($user);
            $cartTicket->setChildQuantity($form->getData()->getChildQuantity());
            $cartTicket->setStudentQuantity($form->getData()->getStudentQuantity());
            $cartTicket->setAdultQuantity($form->getData()->getAdultQuantity());
            $cartTicket->setPensionerQuantity($form->getData()->getPensionerQuantity());

            $this->managerRegistry->getManager()->persist($cartTicket);
            $this->managerRegistry->getManager()->flush();

            /*$this->managerRegistry->getManager()->remove($cartTicket);
            $this->managerRegistry->getManager()->flush();*/

            $cart->add($id);

            $this->addFlash('success', 'Ticket added to cart');

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/add.html.twig', ['form' => $form->createView(), 'cartTicket' => $cartTicket, 'travelSchedule' => $travelSchedule, 'busCompany' => $busCompany->getName()]);
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
    public function deleteFromCart(Request $request, int $id): RedirectResponse
    {
        $cartTicket = $this->managerRegistry->getRepository(CartTicket::class)->findBy(['user' => $this->getUser()]);

        foreach ($cartTicket as $ticket) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();

            $request->getSession()->clear('cart');
        }

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