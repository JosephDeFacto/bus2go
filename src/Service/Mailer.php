<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvoice(User $user, $invoice)
    {
        $email = (new Email())
                ->from('admin.support@no-reply.com')
                ->to(new Address($user->getEmail()))
                ->subject('Your invoice has been delivered to you!')
                ->text('Check the attachment.')
                ->attach($invoice);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $e->getMessage();
        }
    }
}
