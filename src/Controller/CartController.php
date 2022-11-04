<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\TravelSchedule;
use App\Form\CartTypeFormType;
use App\Repository\CartTicketRepository;
use App\Repository\TravelScheduleRepository;
use Couchbase\MatchAllSearchQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CartController extends AbstractController
{
    public const CART_KEY_NAME = 'cart_id';
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

        // get cart's data from session
        if ($request->getSession()->has('session')) {
            dd($request->getSession()->get('session'));
        }

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

        $form = $this->createForm(CartTypeFormType::class, $cartTicket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartTicket->setUser($user);
            $cartTicket->setTravelSchedule($travelSchedule);
            $cartTicket->setQuantity($cartTicket->getQuantity());
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

}