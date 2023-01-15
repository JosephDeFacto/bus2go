<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\Verificator\VerifyEmailInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
class RegistrationController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private VerifyEmailInterface $verifyEmail;

    public function __construct(MailerInterface $mailer, VerifyEmailHelperInterface $helper, VerifyEmailInterface $verifyEmail)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->verifyEmail = $verifyEmail;
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['IS_AUTHENTICATED_FULLY ']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');

            // generate a signed url and email it to the user
            //$signature = $this->verifyEmail->generateSignature('app_verify_email', $user->getId(), $user->getEmail());
           /* $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail()
            );*/

           /* $expiresAt = \DateTime::createFromImmutable($signatureComponents->getExpiresAt());
            $expiresAt->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            $expiryTime = \IntlDateFormatter::formatObject($expiresAt->setTimezone(new \DateTimeZone(date_default_timezone_get())));*/

           /* $email = new TemplatedEmail();
            $email->from('admin.admin@no-reply.com');
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signature->getSignedUrl()]);

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $exception)
            {
                $exception->getDebug();
            }

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request,
            );*/


           /* $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin.admin@no-reply.com', 'ADmin'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );*/
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, User $user, ManagerRegistry $registry): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);
        $entityManager = $registry->getManager();
        $entityManager->flush();


        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
