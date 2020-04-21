<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Users;
use App\Entity\City;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
    public function userID (Request $request, $id)
    {
        $type = $request->get('type', 'departure');  //stockage type departure ou arrival pr le formulaire//

        if ($id) {
            $ride = $this->getDoctrine()
                ->getRepository(Ride::class)
                ->findOneBy([
                    "id" => $id,
                    "user" => $this->getUser()
                ]);

            $city = $ride->{'get'.$type}()->getId();
        } else {
            $ride = new Ride;
            $ride->setUser($this->getUser());
            $city = null;
        }


        $builder = $this->createFormBuilder($ride);

        $builder->add('schedule', TimeType::class, ['label' => 'Horaire']);
        $formModifier = function($form, $city) use ($type) {
            $form->add($type, EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => ['search-city' => true],
                'query_builder' => function (EntityRepository $er) use ($city) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id',$city);
                },
            ]);
        };
        $formModifier($builder, $city);

        $builder->add('spaceAvailable', NumberType::class, ['label' => 'Place disponible']);
        $builder->add('observations', TextType::class, ['label' => 'Observations']);
        $builder->add('save', SubmitType::class, ['label' => 'Valider']);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier, $type) {
            $form = $event->getForm();
            $data = $event->getData();
            if (isset($data[$type])) {
                $formModifier($form, $data[$type]);
            }
        });

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ride);
            $entityManager->flush();

        }

        return $this->render('ride/form.html.twig', [
            'form' => $form->createView(),

        ]);
    }

}
