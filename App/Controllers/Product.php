<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;
use \Core\SendMail;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */
    public function indexAction()
    {
        if(isset($_POST['keyword'])) {
            $text =  Articles::autocomplete($_POST['keyword']);
            $i=0;
           $datalist= ('<datalist id="browsers">');
            foreach ($text as $city){
                if(strlen($_POST['keyword'])  >1){
                    $datalist.= ('<option value=" '.$text[$i]["ville_nom_reel"].'"> ');
                    $i++;
                }
            }
            $datalist.=('<datalist id="browsers">');
            echo $datalist;
            die();
        }



        if (isset($_GET["code"])){
            echo('<script>alert("Certains champs n\'ont pas été saisis") </script>' );

        }
        if(isset($_POST['submit'])) {
            try {
                if (isset($_POST['name']) && isset($_POST['city']) && isset($_POST['description']) && $_POST['name'] !=""
                    && isset($_FILES['picture']["name"]) && $_FILES['picture']["name"] !=""
                    && $_POST['description'] !="" && $_POST['city'] !="" ) {

                    $f=$_POST;
                    $f['user_id'] = $_SESSION['user']['id'];
                    $id = Articles::save($f);
                    $pictureName = Upload::uploadFile($_FILES['picture'], $id);

                    Articles::attachPicture($id, $pictureName);

                    if ($id != ""){
                        header('Location: /product/' . $id);
                    }else{
                        View::renderTemplate('Product/Add.html', [
                            'titre' => $_POST['name'],
                            'description' => $_POST['description'],
                            'ville' => $_POST['city'],
                        ]);
                    }
                }else{
                    echo ('<script> alert("Certains champs sont vides") </script>');
                    View::renderTemplate('Product/Add.html', [
                        'titre' => $_POST['name'],
                        'description' => $_POST['description'],
                        'ville' => $_POST['city'],
                    ]);
                }
            }
            catch
            (\Exception $e){
                throw new \Exception("Une erreur c'est produite. Détail de l'erreur : " . $e);
            }
        }else{
            View::renderTemplate('Product/Add.html');

        }
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);

            if (isset($_POST['messageMailContact']) && $_POST['messageMailContact'] <> ''){
                //Ajout de la sécurité JS
                $regex_for_text =
                    '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
                    'script[^>]*>';
                $messagePourMail = preg_replace("#$regex_for_text#i",'',$_POST['messageMailContact']);

                $resultSendMail = SendMail::sendOneMail($article[0]["email"], 'Nouveau message concernant votre annonce "' . $article[0]["name"] .'" !', $messagePourMail);

            } else {
                $resultSendMail = "";
            }

        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions,
            'resultSendMail' => $resultSendMail
        ]);
    }
}
