<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/google", name="app_login_google")
     */
    public function loginGoogle(): JsonResponse
    {
        $user = $this->getUser();

        $response = new JsonResponse(true);

        $response->headers->setCookie(Cookie::create(
            'GOOGLE_ID', //name//
            $user->getGoogleId(), //value//
            time() + 365 * 24 * 3600,   //expiration//
            '/',   //chemin//
            null,  // domain//
            null, //secure//
            false //http only//
        ));

        return $response;

    }

}
