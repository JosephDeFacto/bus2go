<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Form\CartTypeFormType;
use App\Repository\CartTicketRepository;
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

        $t = $cartTicket->getQuantity();




        return $this->render('cart/index.html.twig', ['cartTickets' => $tickets]);
    }

    /**
     * @Route("/cart/add/{id}"), name="cart_addToCart")
     */
    public function addToCart(Request $request, TravelScheduleRepository $travelScheduleRepository): RedirectResponse
    {
        $id = $request->get('id');

        $user = $this->getUser();

        $travelSchedule = $travelScheduleRepository->find($id);

        $cartTickets = new CartTicket();

        $cartTickets->setUser($user);
        $cartTickets->setTravelSchedule($travelSchedule);
        $cartTickets->setQuantity($travelSchedule->getFee());

        $this->managerRegistry->getManager()->persist($cartTickets);
        $this->managerRegistry->getManager()->flush();

        $this->addFlash('success', 'Ticket added to cart');

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart)", name="cart_increment")
     */
    public function increment(Request $request, TravelScheduleRepository $travelScheduleRepository, CartTicketRepository $cartTicketRepository)
    {
        $id = $request->get('id');

        /*$schedule = $travelScheduleRepository->find($id); */
        $schedule = $travelScheduleRepository->findAll();

        $cart = $cartTicketRepository->findOneBy([
            'travelSchedule' => $schedule,
        ]);

        $cart->setQuantity($cartTicketRepository->findAll()[0]->getQuantity() + 1);
        /*$this->managerRegistry->getManager()->persist($cart);*/
        $this->managerRegistry->getManager()->flush();

        return $this->redirectToRoute('cart_index');

        /*return new JsonResponse(['data' => $cart->getQuantity()]);*/
    }

    /**
     * @Route("cart/decrement/{id}", name="cart_decrement")
     */
    public function decrement(Request $request, TravelScheduleRepository $travelScheduleRepository, CartTicketRepository $cartTicketRepository)
    {
        $id = $request->get('id');

        $schedule = $travelScheduleRepository->find($id);

        $cart = $cartTicketRepository->findOneBy([
            'travelSchedule' => $schedule,
        ]);
        $cart->setQuantity($cartTicketRepository->findAll()[0]->getQuantity() - 1);
        $this->managerRegistry->getManager()->persist($cart);
        $this->managerRegistry->getManager()->flush();

        return $this->redirectToRoute('cart_index');
    }
}
