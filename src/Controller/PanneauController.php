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

            $schedule = $request->request->get('schedule');

            $error = null;
                if (!preg_match('/[0-9]{2}\:[0-9]{2}/', $schedule)) {
                  $error =  "Le format est incorrecte";
                }



            if (! $error) {
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

        }

        $user = $this->getUser(); //je recupere le user connecté//
        $company = $user->getCompany();// je recupere l'id de la compagnie
        $adress = $company->getAdress();//je recupere l' adresse de la coapgnie//


        $arrivals = $this->getDoctrine() //afficher les villes ds la recherche//
        ->getRepository(Ride::class)
        ->findBy([
            "arrival" => null,
            "user" => $user
        ]);

        $departures = $this->getDoctrine() //afficher les villes ds la recherche//
        ->getRepository(Ride::class)
        ->findBy([
            "departure" => null,
            "user" => $user
        ]);


        return $this->render('panneau/index.html.twig', [
            'departures' => $departures,
            'arrivals' => $arrivals,
            'adress'=> $adress,
            'controller_name' => 'PanneauController',
        ]);
    }
}
