<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Zip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CityController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
    *{
    * "results": [
    *     {
    *       "id": 1,
    *       "text": "Nancy"
    *     },
    *     {
    *       "id": 120,
    *       "text": "Lille"
    *     }
    *  ]
    *}
     *
     * @Route("/city/search", name="city_search")
     */
    public function search(Request $request): JsonResponse //obligation de renvoyer une json reponse :recupere requete http, header; $request= parametre de la fonction, passe un service au controller  1 url: dedans la query string///
    {
        $cities = []; //creation de la variable de type tableau //

        $search = $request->query->get('q', null); //recupere le query string q depuis l'url et attribue une valeur par defaut =nul si il n'existe pas //

        if ($search) { //si la variable $search est differente de nul //

            if (preg_match('#^[0-9]{2,}$#', $search)) {    // si  le parmetre $search est un code postal (verif via une regex ) preg match = controle le regex //
                $result = $this->getDoctrine()->getRepository(Zip::class)  // on recupere le model zip ds le mvc  //
                    ->createQueryBuilder('z') //on créé un alias z pr la requete sql//
                    ->where('z.code LIKE :code')//on cherche les codes postaux correspondant en bdd ds la colonne code ds la table zip//
                    ->setParameter('code', $search . '%')// on bin le parametre ds la request pr eviter les injections sql //
                    ->getQuery()//on recupere une instance de l'objet querybuilder de l'orm //
                    ->getResult();//on execute la query et on recupere le resultat et on l'assigne à la variable $result en ligne 25 //

                foreach ($result as $value) { // on boucle les resultats des code postaux recupérés//
                    foreach ($value->getCities() as $city) {//on recupere les villes jointes au code postal un seul et on les boucles //
                        $cities[] = $city;//on ajoute la ville ds le tableu cities //
                    }
                }
            } else if (strlen($search) > 1) {   //sinon si $search (à 1 cractere alphabetique) est une ville strlen = calcul dune chaine de caractere//
                // on réassigne la variable cities avec le resultat des villes recuperees jusque ligne 44//
                $cities = $this->getDoctrine()->getRepository(City::class) // on recupere le model city //
                    ->createQueryBuilder('s') //on créé un alias s pr la requete sql//
                    ->where('s.name LIKE :name') //on cherche les noms des villes correspondant en bdd ds la colonne name de la table city//
                    ->setParameter('name', $search . '%') // on bin le parametre ds la request pr eviter les injections sql //
                    ->getQuery() //on recupere une instance de l'objet querybuilder de l'orm //
                    ->getResult();//on execute la query et on recupere le resultat et on l'assigne à la variable $cities en ligne 39 //
            }
        }

        $response = []; //on prepare une variable pr formater les resultats de type tableau //

        foreach ($cities as $city) { //on liste les villes recuperees //  $city instance de la classe city
            $response[] = [  // on ajoute la ville à la reponse//
                'id' => $city->getId(),//on créé une clé id avec la valeur de l'id de la ville $city //
                'text' => $city->getName(),//on créé une clé text avec la valeur du nom de la ville $city //
            ];
        }

        return $this->json([  //on renvoie un reponse http au format json le plugin a besoin d'un result json avec id et text//
            'results' => $response  //on renvoi les villes dans une clé result//
        ]);
    }

}
