<?php

namespace App\Controller;
use App\Entity\City;
use App\Entity\Ride;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    /**
     * @Route("/panneau/test", name="test")
     */
    public function index(Request $request)
    {
        $search = $request->query->get('search', null);



        if ($search) {

            $result = $this->getDoctrine()->getRepository(City::class)->createQueryBuilder('s')
                ->where('s.name LIKE :name')
                ->setParameter('name', $search . '%')
                ->getQuery()
                ->getResult();

            dump($result);

        }

        $ville = $this->getDoctrine()
        ->getRepository(City::class)
        ->findAll();

        return $this->render('test/index.html.twig', [
            'result' => $result,
            'ville' => $ville,
            'controller_name' => 'TestController',
        ]);
    }

}
