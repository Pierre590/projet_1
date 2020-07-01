<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Adress;
use App\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CompanyController extends AbstractController
{

    /**
     * @Route("/company", name="company")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if ($user && $user->getCompany()) {
            return $this->redirectToRoute('home');
        }

        $error = null;

         if ($user && $request->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if ($this->isCsrfTokenValid('login_company', $submittedToken)) {

                $cityId = (int) $request->request->get('cityId');
                $firstName = (string) $request->request->get('firstname');
                $adress = (string) $request->request->get('adresse');
                $email = (string) $request->request->get('email');
                $phone = (string) $request->request->get('phone');
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

                    $user->setCompany($company);
                    $user->addCompanyRole('ROLE_ADMIN');

                    $entityManager->persist($user);

                    $entityManager->flush();

                    return $this->redirectToRoute('company_code');
                }
            }
        }
        return $this->render('company/index.html.twig');
    }

    /**
     * @Route("/company/code", name="company_code")
     */
    public function code()
    {

        $company = $this->getUser()->getCompany();

        $code = '';
        for ($i = 0; $i < 7; $i++) {
            $code .= chr(rand(ord('a'), ord('z')));
        }
        // voir pr améliorer, ajouter le timestamp autre méthode sans l'id comapny crypté, encodé l'id//
        $code .=  $company->getId();

        $company->setCode($code);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('company/code.html.twig', [
            'code' => $code,
        ]);
    }

}
