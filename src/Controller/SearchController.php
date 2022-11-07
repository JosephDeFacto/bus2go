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
    public function searchForTravel(Request $request, TravelScheduleRepository $travelScheduleRepository): Response
    {
        /*$travelData = $travelScheduleRepository->findAll();
        $result = [];
        foreach ($travelData as $data) {
            $fees = [$data->getFee()];
            $result[] = $travelScheduleRepository->searchForBuses($fees);
        }
        var_dump($result);*/
        /*$departFrom = $request->get('depart_from');
        $travelTo = $request->get('travel_to');*/

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


            return $this->render('searchForTravel/search.html.twig', ['results' => $schedules]);
        }

        return $this->render('searchForTravel/index.html.twig', [
            'form' => $form->createView(),
        ]);

        /*$departFrom = $request->get('depart_from');
        $travelTo = $request->get('travel_to');
        if ($request->isMethod('GET')) {
            $schedules = $travelScheduleRepository->searchForBuses($departFrom, $travelTo);
            foreach ($schedules as $schedule) {

                $results = [];
                if ($schedule['depart_from'] == $departFrom && $schedule['travel_to'] == $travelTo) {
                    $results = [
                        'depart_from' => $schedule['depart_from'],
                        'travel_to' => $schedule['travel_to'],
                    ];
                } else {
                    echo "Travel route does not exists";
                }
            }
            return $this->render('searchForTravel/index.html.twig', [
                'results' => $results,
            ]);

        }
        return $this->render('searchForTravel/index.html.twig');*/
    }
}
