<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\TravelSchedule;
use App\Entity\User;
use App\Form\CartTypeFormType;
use App\Repository\CartTicketRepository;
use App\Repository\PassengerTypeRepository;
use App\Repository\TravelScheduleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param CartTicket $cartTicket
     * @return Response
     */
    public function index(Request $request, CartTicket $cartTicket): Response
    {

        $tickets = $this->managerRegistry->getRepository(CartTicket::class)->findAll();

        return $this->render('cart/index.html.twig', ['cartTickets' => $tickets]);
    }

    /**
     * @Route("/cart/add/{id}"), name="cart_addToCart")
     */
    public function addToCart(CartTicketRepository $cartTicketRepository, Request $request, TravelScheduleRepository $travelScheduleRepository, TravelSchedule $travelSchedule): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $id = $request->get('id');

        $user = $this->getUser();
        $cartTicket = new CartTicket();

        $travelSchedule = $travelScheduleRepository->find($id);

        $form = $this->createForm(CartTypeFormType::class, $cartTicket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setUser($user);
            $cartTicket->setTravelSchedule($travelSchedule);
            $cartTicket->setQuantity($cartTicket->getQuantity());

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


        $form = $this->createForm(CartTypeFormType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setQuantity($cartTicket->getQuantity());
            $entityManager = $registry->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/update.html.twig', ['cartTicket' => $cart, 'form' => $form->createView()]);
    }

    /**
     * @Route("cart/delete/{id}", name="cart_deleteToCart")
     */

    public function deleteFromCart()
    {
        // Riješi ovo
    }

}