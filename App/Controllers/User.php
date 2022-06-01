<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

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
        if(isset($_COOKIE['email'])&&isset($_COOKIE['password'])){
            try{
                $f=[
                    'email'=>$_COOKIE['email'],
                    'password'=>$_COOKIE['password'],
                ];

                $this->login($f);
                header('Location: /account');
            }catch (Exception $e){
                echo $e;
            }
        }

        if(isset($_COOKIE['email'])&&isset($_COOKIE['password'])){
            try{
                $f=[
                    'email'=>$_COOKIE['email'],
                    'password'=>$_COOKIE['password'],
                ];
                $this->login($f);
                header('Location: /account');
            }catch (Exception $e){
                echo $e;
            }
        }
        if(isset($_POST['submit'])){
            try{
                $f = $_POST;
                if(isset($_POST['checkbox'])&&$_POST['checkbox'] == true){
                    setcookie('visitorLogged',true,time()+86400);
                    setcookie("email",$f['email'],time()+86400);
                    setcookie("password",$f['password'],time()+86400);
                }
                $this->login($f);
                // Si login OK, redirige vers le compte
                header('Location: /account');
            } catch(\Exception $e){
                echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
            }
        }
        View::renderTemplate('User/login.html');
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if(isset($_GET["code"])){
            if ( $_GET['code']=='existe'){
                echo ("<script>alert('L\'email utilisé existe déjà') </script>");
            }
            if ( $_GET['code']=='errem'){
                echo ("<script>alert('L\'email utilisé n\'est pas conforme') </script>");
            }
            if ( $_GET['code']=='mdpf'){
                echo ("<script>alert('Les mots de passe ne sont pas identiques') </script>");
            }
            if ( $_GET['code']=='mdpc'){
                echo ("<script>alert('Mot de passe trop court') </script>");
            }
            if ( $_GET['code']=='idf'){
                echo ("<script>alert('identifiants invalides') </script>");
            }
        }
        if(isset($_POST['submit'])){
            try {
                $f = $_POST;
                $email = $f['email'];
                //regex de vérification des emails
                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                $email = (preg_match($regex, $email))?$email:"invalid email";
                if($email == 'invalid email'){
                    header('Location: /register?code=errem');
                    die();
                }
                if($f['password'] != $f['password-check']) {
                    header('Location: /register?code=mdpf');
                    die();
                }else{
                    if(strlen($f['password'])<7 ){
                        header('Location: /register?code=mdpc');
                        die();
                    }else{
                        $regex_for_text =
                            '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
                            'script[^>]*>';
                        $f['username'] = preg_replace("#$regex_for_text#i",'',$f['username']);

                        if($f['username'] == "" || $f['username'] ==" "){
                            header('Location: /register?code=idf');
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
                                header('Location: /register?code=existe');
                            }
                        }
                    }

                }
                // validation
            } catch(\Exception $e){
                echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
            }
        }else{
            View::renderTemplate('User/register.html');
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
                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                $email = (preg_match($regex, $email))?$email:"invalid email";
                if($email == 'invalid email'){
                    header('Location: /login?cod=errem');
                    return false;
                }

                $user = \App\Models\User::getByLogin($data['email']);
                if (Hash::generate($data['password'], $user['salt']) == $user['password']) {
                    $_SESSION['user'] = array(
                        'id' => $user['id'],
                        'username' => $user['username']
                    );
                }else{
                    header('Location: /login?cod=errlog');
                    return false;
                }
            }else{
                header('Location: /login?cod=errlog');
                return false;
            }
            return true;
        } catch (Exception $ex) {
            header('Location: /login?cod=errlog');
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
                setcookie("email","", time()-3600);
                unset($_COOKIE['email']);
                setcookie("password","", time()-3600);
                unset($_COOKIE['password']);
            }
            // Destroy all data registered to the session.
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            header ("Location: /");
            return true;
        } catch(\Exception $e){
            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
        }
    }
}