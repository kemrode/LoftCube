<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use Core\View;
use Exception;

class Search extends \Core\Controller
{
    /**
     * Affichage page search.html qui renvoit le résultat de la recherche
     * --> Liste des choses correspondantes à la recherche
     */
    public function indexSearch($result)
    {
        if(isset($_POST['submit'])){
            try {
                View::renderTemplate('Product/search.html', ["result" => $result]);
            } catch (\Exception $e)
            {
                echo "<script>console.log('Debug Objects:" . $e ."');</script>";
            }
        }
    }

    public function aroundMeSearchResultView($result){
        try {
            View::renderTemplate('Product/search.html', ["result" => $result]);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Fonction permettant de procéder à la recherche de ce qui est saisi
     * dans le champ recherche.
     * sql LIKE
     */
    public function searchingObject()
    {
        try {
            if(isset($_POST['submit'])){
                $object = $_POST['submit'];
                $result = Articles::searchByWording($object);
                return $this->indexSearch($result);
            }
        } catch (\Exception $e) {
            echo "<script>console.log('Debug Objects:" . $e ."');</script>";
        }
    }

    public function searchAround() {
        try {
            $city = $_POST['getCity'];
            $citiesAroundMe = Cities::getAllCitiesAroundMe($city);
            $result = Articles::searchAroundMe($citiesAroundMe);
            if(count($result) == 0 || empty($city)){
                View::renderTemplate("404.html");
            } else {
                return $this->aroundMeSearchResultView($result);
            }
        } catch (\Exception $e) {
            echo "<script>console.log('Debug Objects:" . $e ."');</script>";
            View::renderTemplate("404.html");
        }
    }

}