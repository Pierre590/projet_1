<?php

namespace App\Controller;

use App\Entity\Resa;
use App\Entity\Ride;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RemoveController extends AbstractController
{
    /**
     * @Route("/ride/remove/{id}", name="ride_remove")
     */
    public function index($id)  //ajouter un isgranted//
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
