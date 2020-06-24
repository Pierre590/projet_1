<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class GoogleAuthenticator extends AbstractGuardAuthenticator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
     public function supports(Request $request)
     {
         return 'app_login_google' === $request->attributes->get('_route')
             && $request->isMethod('POST');
     }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return [
            'id_token' => $data['id_token'],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $idToken = $credentials['id_token'];

        if (null === $idToken) {
            return;
        }

        $client = new \Google_Client([
            'client_id' => $_ENV['GOOGLE_CLIENT_ID']  //etape necessaire pr verifier id et token
        ]);

        try {
            $payload = $client->verifyIdToken($idToken); //payload = données, reponse envoyées par google
        } catch (\Exception $e) {  //verifyIdToken depuis mon back vers api google pr verfier id google
            return;
        }

        $user = $this->em
        ->getRepository(Users::class)
        ->findOneBy([
            'googleId' => $payload['sub']
        ]);


        if (!$user) {
            $user = new Users;
            $user
                ->setGoogleId($payload['sub'])
                ->setEmail($payload['email'])
                ->setFirstname($payload['given_name'])
                ->setLastname($payload['family_name']);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Vous devez être connecté pour accéder à cette section'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
