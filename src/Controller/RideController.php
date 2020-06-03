<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Users;
use App\Entity\City;
use App\Entity\Resa;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     *@IsGranted("ROLE_USER")
     */
    public function rides()
    {
        $user = $this->getUser(); //je recupere le user connecté//
        $company = $user->getCompany();// je recupere l'id de la compagnie
        $adress = $company->getAdress();//je recupere l' adresse de la coapgnie//


        $arrivals = $this->getDoctrine() //afficher les arrivés//
        ->getRepository(Ride::class)
        ->findBy([
            "departure" => null,
            "user" => $user
        ]);


        $departures = $this->getDoctrine() //afficher les départs //
        ->getRepository(Ride::class)
        ->findBy([
            "arrival" => null,
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
     *@IsGranted("ROLE_USER")
     */
    public function userID (Request $request, $id)
    {
        $type = $request->query->get('type', 'departure');
         //stockage type departure ou arrival pr le formulaire//
        $user = $this->getUser();

        if ($id) {
            $ride = $this->getDoctrine()
                ->getRepository(Ride::class)
                ->findOneBy([
                    "id" => $id,
                    "user" => $user,
                ]);

            $city = $ride->{'get'.ucfirst($type)}()->getId();
        } else {
            $ride = new Ride;
            $ride->setUser($user);
            $city = null;
        }


        $builder = $this->createFormBuilder($ride);

        $builder->add('schedule', TimeType::class, ['label' => 'Horaire']);
        $formModifier = function($form, $city) use ($type) {
            $form->add($type, EntityType::class, [
                'label' => $type === 'arrival' ? 'Arrivée':'Départ',
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => [
                    'search-city' => true,
                    'style' => 'width: 100%',
                    'required' => true,
                ],
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

            $ride->setCompany($user->getCompany());

            //ride getcity si pas de ville = erreur
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ride);
            $entityManager->flush();

            return $this->redirectToRoute('rides');
        }

        return $this->render('ride/form.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/ride/{id}/resa", name="ride_id_resa", defaults={"id":null})
     *@IsGranted("ROLE_USER")
     */
    public function resa($id)
    {
        $ride = $this->getDoctrine()
            ->getRepository(Ride::class)
            ->find($id);


            if (count($ride->getResas()) === $ride->getSpaceAvailable()) {
                throw new \Exception('plus de place disponible'); //securise le code qd 0 place

            }


            foreach ($ride->getResas() as $resa) {
                if ($resa->getUser() === $this->getUser() ) {
                    throw new \Exception ('user existe deja'); //securise sur un trajet deja resa
                }
            }

        $resa = new Resa; //objet user:null :ride= nul
        $resa->setRide($ride);  //objet user:null :ride= chiffre de la resa
        $resa->setUser($this->getUser());// //objet user = id de l'user :ride= chiffre de la resa


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($resa);
        $entityManager->flush();  //enregistrement en bdd

        return $this->redirectToRoute('panneau_company', [
            'company' => $this->getUser()->getCompany()->getId()
        ]);

    }

    /**
     * @Route("/ride/remove/{id}", name="ride_remove")
     */
    public function remove($id)  //ajouter un isgranted//
    {
        $repository = $this->getDoctrine()->getRepository(Ride::class);
        $ride = $repository->findOneBy([
            'user' => $this->getUser(),
            'id' => $id
        ]);

        if (!$ride) {
            return $this->redirectToRoute('rides');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ride);
        $entityManager->flush();

        return $this->redirectToRoute('rides');

    }
}
