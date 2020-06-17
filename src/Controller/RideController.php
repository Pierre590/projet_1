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
use Symfony\Component\Validator\Constraints as Assert;


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
        //stockage type departure ou arrival pr le formulaire
        $type = $request->query->get('type', 'departure');

        $user = $this->getUser();

        // Si edition d'un trajet
        if ($id) {
            // Récupérer le trajet en bdd si il existe
            // et qu'il appartient bien à l'user connecté
            $ride = $this->getDoctrine()
                ->getRepository(Ride::class)
                ->findOneBy([
                    "id" => $id,
                    "user" => $user,
                ]);

            if ($type === 'departure') {
                $city = $ride->getDeparture();
            } else {
                $city = $ride->getArrival();
            }
            $cityId = $city->getId();
        } else { // Sinon création d'un trajet
            $ride = new Ride;
            $ride->setUser($user);
            $cityId = null;
        }

        // Création du formulaire
        $builder = $this->createFormBuilder($ride);

        $builder->add('schedule', TimeType::class, ['label' => 'Horaire']);

        // Permet de modifier le form au chargement de la page (GET)
        // Et avant de poster le formulaire (PRE_SUBMIT)
        // Sinon Symfony invalide les données car la ville est ajoutée dans le select côté front
        $formModifier = function($form, $cityId) use ($type) {
            $form->add($type, EntityType::class, [
                'label' => $type === 'arrival' ? 'Arrivée':'Départ',
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => [
                    'search-city' => true,
                    'style' => 'width: 100%',
                    'required' => true,
                ],
                'query_builder' => function (EntityRepository $er) use ($cityId) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $cityId);
                },
            ]);
        };
        $formModifier($builder, $cityId);

        $builder->add('spaceAvailable', NumberType::class, [
            'label' => 'Place disponible',
            'attr' => [
                'placeholder' => 'minimum 1 place.',
            ],
            'constraints' => [new Assert\NotBlank()] //securite evite de ne pas valider le form sans valeur
        ]);
        $builder->add('observations', TextType::class, [
            'label' => 'Observations',
            'attr' => [
                'placeholder' => 'ras si champ vide.',
            ],
            'constraints' => [new Assert\NotBlank()]
        ]);
        $builder->add('save', SubmitType::class, ['label' => 'Valider']);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier, $type) {
            $form = $event->getForm();
            $data = $event->getData();
            // Intégration du nouvel ID city dans le select
            // Pour que Symfony valide le Formulaire
            if (isset($data[$type])) {
                $formModifier($form, $data[$type]);
            }
        });

        $form = $builder->getForm();

        $form->handleRequest($request);


        // Vérification que la ville (departure ou arrival) n'est pas null
        // Et que le reste du form est valide
        if ($form->isSubmitted() && $form->get($type)->getData() && $form->isValid()) {

            $ride->setCompany($user->getCompany());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ride);
            $entityManager->flush();

            return $this->redirectToRoute('rides');
        }

        return $this->render('ride/form.html.twig', [
            'type' => $type,
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
