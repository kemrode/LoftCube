<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Cookie;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;
use App\Utility\Regex;
use \Core\SendMail;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if (isset($_GET["code"])){
            switch ($_GET["code"]){
                case "errem":
                    $messageErreur = "Erreur : l'email utilisé n'est pas conforme";
                    break;
                case "errlog":
                    $messageErreur = "Erreur : email ou mot de passe incorrect";
                    break;
                default:
                    $messageErreur = "Erreur : Cette erreur n'est pas référencée !";
                    break;
            }
        } else {
            $messageErreur = "";
        }

        if ((isset($_COOKIE["visitorLogged"]) && $_COOKIE["visitorLogged"]) || (isset($_SESSION['user']['username']))){
            header('Location: /');
        }

        if(isset($_POST['submit'])){
            try{
                // Si login OK, redirige vers le compte
                if ($this->login($_POST)){
                    header('Location: /account');
                }

            } catch(\Exception $e){
                echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
            }
        }

        $valueemail = (isset($_GET["email"])) ? $_GET["email"] : "";

        View::renderTemplate('User/login.html',[
            'emailValue' => $valueemail,
            'messageErreur' => $messageErreur
        ]);
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if (isset($_GET["code"])){
            switch ($_GET["code"]){
                case "existe":
                    $messageErreur = "Erreur : Cette adresse email est déjà utilisé";
                    break;
                case "errem":
                    $messageErreur = "Erreur : l'email utilisé n'est pas conforme";
                    break;
                case "errlog":
                    $messageErreur = "Erreur : email ou mot de passe incorrect";
                    break;
                case "mdpf":
                    $messageErreur = "Erreur : Les deux mots de passes ne sont pas identiques";
                    break;
                case "mdpc":
                    $messageErreur = "Erreur : Votre mot de passe est trop court";
                    break;
                case "idf":
                    $messageErreur = "Erreur : Identifiant non valide";
                    break;
                default:
                    $messageErreur = "Erreur : Cette erreur n'est pas référencée !";
                    break;
            }
        } else {
            $messageErreur = "";
        }

        if(isset($_POST['submit'])){
            try {
                $f = $_POST;
                $email = $f['email'];
                //regex de vérification des emails
                $email = Regex::regexEmail($email);

                if($email == 'invalid email'){
                    header('Location: /register?code=errem&username=' . $_POST["username"]);
                    die();
                }
                if($f['password'] != $f['password-check']) {
                    header('Location: /register?code=mdpf&username=' . $_POST["username"] . '&email=' . $_POST["email"]);
                    die();
                }else{
                    if(strlen($f['password'])<7 ){
                        header('Location: /register?code=mdpc&username=' . $_POST["username"] . '&email=' . $_POST["email"]);
                        die();
                    }else{
                        $f['username']= Regex::regexAntiScript($f['username']) ;

                        if($f['username'] == "" || $f['username'] ==" "){
                            header('Location: /register?code=idf&email=' . $_POST["email"]);
                            die();
                        }else{
                            $verif = \App\Models\User::verifUser($f['email']);
                            if ($verif['count']==0) {
                                $this->register($f);
                                $data = array(
                                    "email" => $f['email'],
                                    "password" => $f['password'],
                                );
                                $this->login($data);
                                header('Location: /account');
                            }
                            else{
                                header('Location: /register?code=existe&username=' . $_POST["username"]);
                            }
                        }
                    }

                }
                // validation
            } catch(\Exception $e){
                echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
            }
        }else{
            $valueUsername= (isset($_GET["username"])) ? $_GET["username"] : "";
            $valueEmail = (isset($_GET["email"])) ? $_GET["email"] : "";
            View::renderTemplate('User/register.html', [
                "messageErreur" => $messageErreur,
                "usernameValue" => $valueUsername,
                "emailValue" => $valueEmail
            ]);
        }
    }
    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        try{
            $articles = Articles::getByUser($_SESSION['user']['id']);
            $count = is_null(Articles::getcountByUser($_SESSION['user']['id'])) ? 0 : Articles::getcountByUser($_SESSION['user']['id']);
            $countview = is_null(Articles::getcountviewByUser($_SESSION['user']['id'])) ? 0 : Articles::getcountviewByUser($_SESSION['user']['id']);
            if(isset($_GET['arg'])&& ($_GET['arg'] == 'pop' ||($_GET['arg'] == 'rec' ))){
                $arg =$_GET['arg'];

            }else {
                $arg = null;
            }

        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }

        View::renderTemplate('User/account.html', [
            'articles' => $articles,
            'nb_art' => $count,
            'nb_vue' => $countview,
            'arg' => $arg,

        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);
            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);
            $data = [
                'email'=>$data['email'],
                "password" =>$data['password']
            ];
            return $userID;
        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            Utility\Flash::danger($ex->getMessage());
        }
        $this->login($data);
    }

    private function login($data){
        try {
            if(isset($data['email']) &&( isset($data['password'])
                    && strlen($data['password'])>7
                ) ){
                $email = $data['email'];
                //regex de vérification des emails
                $email = Regex::regexEmail($data['email']);

                if($email == 'invalid email'){
                    header('Location: /login?code=errem');
                    return false;
                }

                $user = \App\Models\User::getByLogin($data['email']);
                if (Hash::generate($data['password'], $user['salt']) == $user['password']) {
                    $_SESSION['user'] = array(
                        'id' => $user['id'],
                        'username' => $user['username']
                    );

                    //Si l'utilisateur souhaite sauvegarder sa session par cookie :
                    if(isset($data['checkbox'])&&$data['checkbox'] == true){
                        Cookie::setCookies($data['email'], $_SESSION["user"]["username"], $_SESSION["user"]["id"]);

                    }

                    return true;
                }else{
                    header('Location: /login?code=errlog&email=' . $data['email']);
                    return false;
                }
            }else{
                header('Location: /login?code=errlog&email=' . $data['email']);
                return false;
            }
            return true;
        } catch (Exception $ex) {
            header('Location: /login?code=errlog&email=' . $data['email']);
            die();
            // TODO : Set flash if error
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }
    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {
        try{
            if (isset($_COOKIE)){
                Cookie::delCookies() ;
            }
            // Destroy all data registered to the session.
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                Cookie::delCookies2() ;
            }
            session_destroy();
            header ("Location: /");
            return true;
        } catch(\Exception $e){
            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
        }
    }

    /**
     * Affiche la page du compte
     */
    public function passwordrecoveryAction()
    {
        try{

            if (isset($_POST["email"]) && !empty($_POST["email"])){
                $email = Regex::regexEmail($_POST['email']);

                if($email == 'invalid email'){
                    View::renderTemplate('User/passwordrecovery.html',[
                        'messageErreur' => "L'adresse email n'est pas valide"
                    ]);
                }

                //Création du mot de passe aléatoire :
                $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $pass = array();
                $combLen = strlen($comb) - 1;
                for ($i = 0; $i < 12; $i++) {
                    $n = rand(0, $combLen);
                    $pass[] = $comb[$n];
                }

                $messagePourMail = "Voici votre nouveau mot de passe : " . implode($pass);

                $salt = Hash::generateSalt(32);

                $newPassword = Hash::generate(implode($pass), $salt);

                var_dump("Password = " . $newPassword);
                var_dump("Salt = " . $salt);

                if(\App\Models\User::updatePasswordFromEmail($_POST["email"], $newPassword, $salt)){
                    $resultSendMail = SendMail::sendOneMail($_POST["email"], 'Réinitialisation de votre mot de passe !', $messagePourMail);

                    if ($resultSendMail){
                        View::renderTemplate('User/passwordrecovery.html', [
                            "messageSucces" => "Un nouveau mot de passe a été généré, si cette adresse email est lié à un compte, vous recevrez le message dans quelques instants."
                        ]);
                    }
                } else {
                    View::renderTemplate('User/passwordrecovery.html', [
                        "messageErreur" => "Une erreur c'est produite pendant la création du nouveau mot de passe, veuillez réessayer plus tard"
                    ]);
                }

            }

            View::renderTemplate('User/passwordrecovery.html');

        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }


    }
}