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
    public function codeEnt()
    {

        $repository = $this->getDoctrine()->getRepository(Company::class);

        $codeEnt = $repository->findOneBy([
            'code' => 'yagxnkx2'
        ]);

        dump($codeEnt);
        die;

        if ($codeEnt) {
            $this->getUser()->setCompany($id);
        }




        return $this->render('home/user.html.twig', [

        ]);
    }

}
