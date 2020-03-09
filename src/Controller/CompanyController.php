<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Adress;
use App\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index(Request $request)
    {

         if ($request->isMethod('POST')) {

            $cityId = $request->request->get('cityId');
            $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);

            $entityManager = $this->getDoctrine()->getManager();

            $firstName = $request->request->get('firstname');
            $adress = $request->request->get('adresse');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');

            $adresse = new Adress();

            $adresse->setAdress($adress);
            $adresse->setCity($city);

            $company = new Company();

            $company->setFirstname($firstName);
            $company->setAdress($adresse);
            $company->setEmail($email);
            $company->setPhone($phone);

            $entityManager->persist($company);

            $entityManager->flush();
        }
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
}
