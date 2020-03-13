<?php

namespace App\Controller;
use App\Entity\City;
use App\Entity\Ride;
use App\Entity\Zip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    /**
     * @Route("/city/search", name="city_search")
     */
    public function search(Request $request)
    {
        $cities = [];

        $search = $request->query->get('q', null);

        if ($search) {

            if (preg_match('#^[0-9]{2,}$#', $search)) {    // code postal Escript  //
                $result = $this->getDoctrine()->getRepository(Zip::class)  // co table zip  //
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
            } else if (strlen($search) > 1) {
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
