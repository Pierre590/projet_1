<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Users;
use App\Entity\City;
use App\Entity\Resa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ResaController extends AbstractController
{
    /**
     * @Route("/resa", name="resa")
     */
    public function index()
    {
        $user = $this->getUser(); //je recupere le user connecté//
        $company = $user->getCompany();// je recupere l'id de la compagnie
        $adress = $company->getAdress();//je recupere l' adresse de la compagnie//


        $arrivals = $this->getDoctrine() //afficher les arrivés//
        ->getRepository(Ride::class)
        ->findByUserResa($user->getId(), 'arrival');


        $departures = $this->getDoctrine() //afficher les départs //
        ->getRepository(Ride::class)
        ->findByUserResa($user->getId(), 'departure');




        return $this->render('resa/index.html.twig', [
            'arrivals'  => $arrivals,
            'departures' => $departures,
            'adress'=> $adress,
         ]);

    }
    /**
     * @Route("/resa/remove/{id}", name="resa_remove")
     */
    public function remove($id)  //ajouter un isgranted//
    {
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Resa::class);
        $resa = $repository->findOneBy([
             'user' => $user,
             'id' => $id
        ]);

        if ($resa) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panneau_company', [
            'company' => $user->getCompany()->getId(),
        ]);

    }
}
