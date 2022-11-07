<?php

namespace App\Controller;

use App\Entity\CartTicket;
use App\Entity\TravelSchedule;
use App\Form\CartTypeFormType;
use App\Form\SearchFormType;
use App\Repository\BusCompanyRepository;
use App\Repository\CartRepository;
use App\Repository\CartTicketRepository;
use App\Repository\TravelScheduleRepository;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request, TravelScheduleRepository $travelScheduleRepository, BusCompanyRepository $busCompanyRepository): Response
    {
        $busCompanies = $busCompanyRepository->findAll();

        $form = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departFrom = $form->getData()->getDepartFrom();
            $travelTo = $form->getData()->getTravelTo();
            $departingOn = $form->getData()->getDepartingOn();
            $returningOn = $form->getData()->getReturningOn();

            $schedules = $travelScheduleRepository->searchForBuses($departFrom, $travelTo, $departingOn, $returningOn);

            $countSearchRows = $travelScheduleRepository->countSearchResult($departFrom, $travelTo, $departingOn, $returningOn);


            return $this->render('index/index.html.twig', ['results' => $schedules, 'rows' => $countSearchRows]);
            /*return $this->render('searchForTravel/search.html.twig', ['results' => $schedules]);*/
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
