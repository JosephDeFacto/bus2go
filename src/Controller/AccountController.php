<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\ObjectIterator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(UserRepository $userRepository): Response
    {
        $authenticatedUser = $this->getUser();
        $userData = $userRepository->findAll();


        /* I have no idea why I created this ObjectIterator */
        $iterator = new ObjectIterator($authenticatedUser);

        $all = $iterator->all();



        return $this->render(
            'account/index.html.twig',
            [
                'user' => $authenticatedUser,
                'userData' => $userData,
                'all' => $all,
            ]
        );
    }

    /**
     * @Route("/account", name="app_account")
     */
    public function myTickets(UserRepository $userRepository): Response
    {
        $data = ['Hello'];

        return $this->render('account/myTickets.html.twig', ['data' => $data]);
    }
}
