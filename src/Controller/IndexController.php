<?php

namespace App\Controller;

use App\Repository\TravelScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET", "POST"})
     */
    public function index(Request $request, TravelScheduleRepository $travelScheduleRepository): Response
    {

        $form = $this->createForm(SearchFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departFrom = $form->getData()->getDepartFrom();
            $travelTo = $form->getData()->getTravelTo();
            $departingOn = $form->getData()->getDepartingOn();

            $schedules = $travelScheduleRepository->searchForBuses($departFrom, $travelTo);


            $countSearchRows = $travelScheduleRepository->countSearchResult($departFrom, $travelTo);

            return $this->render('index/search_results.html.twig', ['results' => $schedules, 'rows' => $countSearchRows,
                                                                'departOn' => $departingOn, 'departFrom' => $departFrom, 'travelTo' => $travelTo,
            ]);
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/search", name="app_search", methods={"POST"})
     */
    public function search(Request $request, TravelScheduleRepository $travelScheduleRepository): JsonResponse
    {

        $departFrom = $request->request->get('departFrom');
        $travelTo = $request->request->get('travelTo');

        $schedules = $travelScheduleRepository->searchForBuses($departFrom, $travelTo);

        $data = [];

        foreach ($schedules as $schedule) {
            $data[] = $schedule->getDepartFrom();
            $data[] = $schedule->getTravelTo();
        }

        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }
}
