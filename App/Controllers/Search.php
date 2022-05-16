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
    public function indexSearch()
    {
        if(isset($_POST['submit'])){
            try {
                View::renderTemplate('User/Search.html');
            } catch (\Exception $e)
            {
                echo "<script>console.log('Debug Objects:" . $e ."');</script>";
            }
        }
    }

    /**
     * Fonction permettant de procéder à la recherche de ce qui est saisi
     * dans le champ recherche.
     * sql LIKE
     */
    public function searchingObject()
    {
        if(isset($_POST['submit'])){
            var_dump($_POST['submit']);
    }
        try {
            $object = $_POST['submit'];
            var_dump($object);
            $result = Articles::searchByWording($object);
            var_dump($result);
            View::renderTemplate('Home/index.html');
        } catch (\Exception $e) {
            echo "<script>console.log('Debug Objects:" . $e ."');</script>";
        }
    }

}