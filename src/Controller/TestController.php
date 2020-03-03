<?php

namespace App\Controller;
use App\Entity\City;
use App\Entity\Ride;
use App\Entity\Zip;
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
        $result = [];

        $search = $request->query->get('search', null);

        if (strlen($search) < 4) {
            $search = null;
        }

        if ($search) {

            $result = $this->getDoctrine()->getRepository(City::class)->createQueryBuilder('s')
                ->where('s.name LIKE :name')
                ->setParameter('name', $search . '%')
                ->getQuery()
                ->getResult();

        }

        return $this->render('test/index.html.twig', [
            'result'=> $result,
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/panneau/search", name="search")
     */
    public function search(Request $request)
    {
        $cities = [];

        $search = $request->query->get('q', null);

        if ($search) {

            if (preg_match('#^[0-9]{2,}$#', $search)) {
                $result = $this->getDoctrine()->getRepository(Zip::class)
                    ->createQueryBuilder('z')
                    ->where('z.code LIKE :code')
                    ->setParameter('code', $search . '%')
                    ->getQuery()
                    ->getResult();

                foreach ($result as $value) {
                    foreach ($value->getCities() as $city) {
                        $cities[] = $city;
                    }
                }
            } else if (strlen($search) > 2) {
                $cities = $this->getDoctrine()->getRepository(City::class)
                    ->createQueryBuilder('s')
                    ->where('s.name LIKE :name')
                    ->setParameter('name', $search . '%')
                    ->getQuery()
                    ->getResult();
            }
        }

        $response = [];

        foreach ($cities as $city) {
            $response[] = [
                'id' => $city->getId(),
                'text' => $city->getName(),
            ];
        }


        return $this->json([
            'results' => $response
        ]);
    }

}
