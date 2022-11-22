<?php

namespace App\Controller;

use App\Repository\BusCompanyRepository;
use App\Repository\TravelScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchFormType;
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

        $form = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departFrom = $form->getData()->getDepartFrom();
            $travelTo = $form->getData()->getTravelTo();
            $departingOn = $form->getData()->getDepartingOn();
            /*$returningOn = $form->getData()->getReturningOn();*/

            $schedules = $travelScheduleRepository->searchForBuses($departFrom, $travelTo, $departingOn/*, $returningOn*/);

            $countSearchRows = $travelScheduleRepository->countSearchResult($departFrom, $travelTo /*$departingOn, $returningOn*/);


            return $this->render('index/index.html.twig', ['results' => $schedules, 'rows' => $countSearchRows, 'departOn' => $departingOn]);
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
