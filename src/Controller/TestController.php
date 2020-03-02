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

        if (count($search) < 3) {

        }

        if ($earch) {

            $result = $this->getDoctrine()->getRepository(City::class)->createQueryBuilder('s')
                ->where('s.name LIKE :name')
                ->setParameter('name', $search . '%')
                ->getQuery()
                ->getResult();

            dump($result);

        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

}
