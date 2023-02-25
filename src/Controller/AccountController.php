<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\ObjectIterator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="user_account", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {

        $user = $this->getUser();


        $userData = $userRepository->find($user);

        return $this->render('account/index.html.twig', ['userData' => $userData]);
    }

    /**
     * @Route("/account", name="app_account")
     */
    public function myTickets(UserRepository $userRepository): Response
    {
        return new Response('');
    }

    /**
     * @Route("/account/edit", name="user_edit", methods={"GET", "POST"})
     */

    public function edit()
    {

    }

    /**
     * @Route("/account/change-password", name="user_change_password", methods={"GET", "POST"})
     */
}
