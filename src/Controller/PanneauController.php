<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Ride;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PanneauController extends AbstractController
{
    /**
     * @Route("/panneau", name="panneau")
     */
    public function index(Request $request)
    {

        if ($request->isMethod('POST')) {

            $entityManager = $this->getDoctrine()->getManager();

            $schedule = $request->request->get('schedule');
            $departure = $request->request->get('departure');
            $spaceAvailable = $request->request->get('spaceAvailable');
            $observations = $request->request->get('observations');

            $createRide = new Ride();

            $createRide->setSchedule(\DateTime::createFromFormat('H:i', $schedule));
            $createRide->setArrival($departure);
            $createRide->setUser($this->getUser());
            $createRide->setSpaceAvailable($spaceAvailable);
            $createRide->setObservations($observations);

            $entityManager->persist($createRide);

            $entityManager->flush();
        }

        $ville = $this->getDoctrine()
        ->getRepository(City::class)
        ->findAll();

        

        return $this->render('panneau/index.html.twig', [
            'ville' => $ville,
            'controller_name' => 'PanneauController',
        ]);
    }
}
