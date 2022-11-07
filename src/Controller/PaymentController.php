<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\Order;
use App\Entity\TravelSchedule;
use Doctrine\Common\Collections\ArrayCollection;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payum", name="app_payment")
     */
    public function index(): Response
    {
       return new Response('');
    }
}
