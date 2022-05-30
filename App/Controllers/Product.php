<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

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
            if (isset($_POST['messageMailContact']) && $_POST['messageMailContact'] <> ''){
                //$resultSendMail = SendMail::sendOneMail('buland2001@gmail.com', 'Nouveau message concernant votre annonce !', $_POST['messageMailContact']);
                $resultSendMail = 'OK';

            } else {
                $resultSendMail = "";
            }


            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
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
