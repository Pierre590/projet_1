<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class RideController extends AbstractController
{
    /**
     * @Route("/rides", name="rides")
     */
    public function rides()
    {
        $user = $this->getUser(); //je recupere le user connecté//
        $company = $user->getCompany();// je recupere l'id de la compagnie
        $adress = $company->getAdress();//je recupere l' adresse de la coapgnie//


        $arrivals = $this->getDoctrine() //afficher les arrivés//
        ->getRepository(Ride::class)
        ->findBy([
            "arrival" => null,
            "user" => $user
        ]);

        $departures = $this->getDoctrine() //afficher les départs //
        ->getRepository(Ride::class)
        ->findBy([
            "departure" => null,
            "user" => $user
        ]);



        return $this->render('ride/rides.html.twig', [
            'arrivals'  => $arrivals,
            'departures' => $departures,
            'adress'=> $adress,
        ]);
    }
    /**
     * @Route("/ride/{id}", name="ride_id", defaults={"id":null})
     */
    public function userID ($id)
    {
        if ($id) {
            $ride = $this->getDoctrine()
                ->getRepository(Ride::class)
                ->find($id)
            ;
        } else {
            $ride = new Ride;
        }

        $form = $this->createFormBuilder($ride)
            ->add('schedule', TimeType::class, ['label' => 'Horaire'])
            ->add('departure', TextType::class, ['label' => 'Départ'])
            ->add('spaceAvailable', TextType::class, ['label' => 'Place disponible'])
            ->add('observations', TextType::class, ['label' => 'Observations'])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        return $this->render('ride/id.html.twig', [
            'form' => $form->createView(),

        ]);
    }

}
