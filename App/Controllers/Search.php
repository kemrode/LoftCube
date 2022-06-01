<?php

namespace App\Controllers;

use App\Models\Articles;
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

    public function aroundMeSearch($result){
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
        $city = $_POST['getCity'];
        if ($_POST['getCity'] ==""){
            View::renderTemplate("404.html");
        }else{
            try {
                $citiesAroundMe = Articles::getAllCitiesAroundMe($city);
                $result = Articles::searchAroundMe($citiesAroundMe);
                if(count($result) == 0){
                    View::renderTemplate("404.html");
                } else {
                    return $this->aroundMeSearch($result);
                }
            } catch (\Exception $e) {
                echo "<script>console.log('Debug Objects:" . $e ."');</script>";
            }
        }
    }
}