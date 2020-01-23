<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PanneauController extends AbstractController
{
    /**
     * @Route("/panneau", name="panneau")
     */
    public function index()
    {
        return $this->render('panneau/index.html.twig', [
            'controller_name' => 'PanneauController',
        ]);
    }
}
