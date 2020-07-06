<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/user/code", name="home_user_code")
     */
    public function codeEnt(Request $request)
    {

        $code = (string) $request->request->get('codeEnt'); 

        $error = null;


        if ($code) {
            $repository = $this->getDoctrine()->getRepository(Company::class);

            $company = $repository->findOneBy([
                'code' => $code   //recup via methode post.//
            ]);
            if ($company) {
                $this->getUser()->setCompany($company);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

            }else {
                //affciher un sms erreur code incorrect
                $error = 'code_invalide';
            }
        }

        return $this->render('home/user.html.twig', [
            'error' => $error,  //a envoyer à la vue pr generer un sms d'erreur en ca de mauvais code..
        ]);
    }

}
