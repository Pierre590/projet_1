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
        $error = null;

         if ($request->isMethod('POST')) {

            $cityId = $request->request->get('cityId');
            $firstName = $request->request->get('firstname');
            $adress = $request->request->get('adresse');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $city = $this->getDoctrine()->getRepository(City::class)->find($cityId);

            if (!$city) {
                $error = "Veuiller sélectionner une ville";
            } else if (!$firstName) {
                $error = "Veuiller renseigner le nom de l'entreprise";
            } else if (!$adress) {
                $error = "Veuiller renseigner une adresse";
            } else if (!$email) {
                $error = "Veuiller renseigner un email valide";
            } else if (!$phone) {
                $error = "Veuiller renseigner un numéro de téléphone";
            } else {
                $entityManager = $this->getDoctrine()->getManager();


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
        }
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
}
