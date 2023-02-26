<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Helper\ObjectIterator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

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
     * @Route("/change-password", name="user_change_password", methods={"GET", "POST"})
     */
    public function changePassword(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, LogoutUrlGenerator $logoutUrlGenerator): Response
    {
        $form  = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));
            $entityManager->flush();

            return $this->redirect($logoutUrlGenerator->getLogoutPath());
        }

        return $this->render('account/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
