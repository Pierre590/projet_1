<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Ride;
use App\Entity\Company;
use App\Entity\Users;
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
            $arrival = $request->request->get('arrival', null);
            $departure = $request->request->get('departure', null);
            $spaceAvailable = $request->request->get('spaceAvailable');
            $observations = $request->request->get('observations');

            $idCity = !is_null($arrival) ? $arrival : $departure;

            $city = $this->getDoctrine()
                ->getRepository(City::class)
                ->find($idCity);


            $createRide = new Ride();

            $createRide->setSchedule(\DateTime::createFromFormat('H:i', $schedule));

            if (!is_null($arrival)){
                $createRide->setArrival($city);
            }else{
                $createRide->setDeparture($city);
            }
            $createRide->setUser($this->getUser());
            $createRide->setSpaceAvailable($spaceAvailable);
            $createRide->setObservations($observations);

            $entityManager->persist($createRide);

            $entityManager->flush();
        }

        $ville = $this->getDoctrine() //afficher les villes ds la recherche//
        ->getRepository(City::class)
        ->findAll();

        $user = $this->getUser(); //je recupere le user connecté//
        $adress = $user->getAdress(); //je recupere son adresse//
        $name = $adress->getCity();// je recupere la ville (city)//
        $company = $user->getCompany();

        return $this->render('panneau/index.html.twig', [
            'ville' => $ville,
            'adress'=> $adress,
            'controller_name' => 'PanneauController',
        ]);
    }
}
