<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PanneauController extends AbstractController
{
    /**
     * @Route("/panneau/{company}", name="panneau_company")
     */
    public function index($company)
    {

        $company = $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($company);



        $arrivals = $this->getDoctrine() //afficher les villes ds la recherche//
        ->getRepository(Ride::class)
        ->findBy([
            "company" => $company,
            "departure" => null,
        ]);



        $departures = $this->getDoctrine() //afficher les villes ds la recherche//
        ->getRepository(Ride::class)
        ->findBy([
            "company" => $company,
            "arrival" => null,
        ]);

        return $this->render('panneau/index.html.twig', [
            'departures' => $departures,
            'arrivals' => $arrivals,
            'company'=> $company,
        ]);
    }

}
