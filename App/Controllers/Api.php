<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use \Core\View;
use Exception;

/**
 * API controller
 * @OA\Info(title="LoftCube API", version="0.1")
 */
class Api extends \Core\Controller
{

    /**
     * Affiche la liste des articles / produits pour la page d'accueil
     *
     * @throws Exception
     *
     * @OA\Get(
     *     path="/Api/Products?sort={sort}",
     *     summary="Récupérer tous les produits",
     *     tags={"Gets"},
     *     @OA\Parameter(description="Trie ('views' ou 'date')", name="sort", in="path"),
     *     @OA\Response(response="200", description="La récupération des données est correct"),
     *     @OA\Response(response="404", description="Impossible de récupérer les données")
     *
     * )
     */
    public function ProductsAction()
    {
        try{
            $query = $_GET['sort'];

            $articles = Articles::getAll($query);

            header('Content-Type: application/json');
            echo json_encode($articles);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    /**
     * Recherche dans la liste des villes
     *
     * @throws Exception
     *
     * @OA\Get(
     *     path="/Api/Cities",
     *     summary="Récupérer toutes les villes",
     *     tags={"Gets"},
     *     @OA\Response(response="200", description="La récupération des données est correct"),
     *     @OA\Response(response="404", description="Impossible de récupérer les données")
     *
     * )
     */
//    public function CitiesAction(){
//        try{
//            $cities = Cities::search($_GET['query']);
//
//            header('Content-Type: application/json');
//            echo json_encode($cities);
//        } catch(\Exception $e){
//
//            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
//
//        }
//    }
}
