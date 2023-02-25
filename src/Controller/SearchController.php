<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\TravelScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{

    /**
     * @Route("/searchForTravel", name="app_search", methods="GET")
     */
    /*public function searchForTravel(Request $request, TravelScheduleRepository $travelScheduleRepository): Response
    {

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


            return $this->render('searchForTravel/index.html.twig', ['results' => $schedules]);
        }

        return $this->render('searchForTravel/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }*/
}
