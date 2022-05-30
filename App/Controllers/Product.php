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

        if(isset($_POST['submit'])) {

            try {
                $f = $_POST;

                // TODO: Validation

                $f['user_id'] = $_SESSION['user']['id'];
                $id = Articles::save($f);

                $pictureName = Upload::uploadFile($_FILES['picture'], $id);

                Articles::attachPicture($id, $pictureName);

                header('Location: /product/' . $id);
            } catch (\Exception $e){
                    var_dump($e);
            }
        }

        View::renderTemplate('Product/Add.html');
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

            var_dump($article);

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
