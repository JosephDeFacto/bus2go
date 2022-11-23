<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Helper\InvoiceGenerator;
use App\Repository\OrderRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    private InvoiceGenerator $invoiceGenerator;
    private ManagerRegistry $managerRegistry;
    private Mailer $mailer;

    public function __construct(ManagerRegistry $managerRegistry, InvoiceGenerator $invoiceGenerator, Mailer $mailer)
    {
        $this->invoiceGenerator = $invoiceGenerator;
        $this->managerRegistry = $managerRegistry;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/invoice", name="app_invoice")
     * @param Pdf $pdf
     * @param OrderRepository $orderRepository
     * @return RedirectResponse
     */
    public function index(Pdf $pdf, OrderRepository $orderRepository): RedirectResponse
    {
        $user = $this->getUser();

        $orders = $orderRepository->findOneBy(['user' => $user]);
        $lastID = $orderRepository->fetchLastRow($user);

        $invoiceNumber = $this->invoiceGenerator->generateInvoicer($lastID, 5, "F-");

        $invoice = new Invoice();

        $invoice->setInvoiceNumber($invoiceNumber);
        $invoice->setIssueDate(new \DateTime('now'));
        $invoice->setIsSent(true);
        $invoice->setUser($user);
        $invoice->setBusCompany(null);
        //$invoice->setOrders($orders);

        $this->managerRegistry->getManager()->persist($invoice);
        $this->managerRegistry->getManager()->flush();

        $html = $this->render('invoice/index.html.twig', ['invoice' => $invoice]);

        $invoice = $pdf->getOutputFromHtml($html);

        $this->mailer->sendInvoice($user, $invoice);

        return $this->redirectToRoute('app_index');
    }
}
