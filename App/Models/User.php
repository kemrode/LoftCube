<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model {

    /**
     * Crée un utilisateur
     */

    public static function verifUser($data)
    {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT COUNT(*) AS "count" FROM users WHERE email = :email;');

        $stmt->bindParam(':email', $data);

        try {
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch(\Exception $e){
        }
    }

    public static function createUser($data) {

        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password,:salt)');
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);
        try {

            $stmt->execute();

        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();
        $stmt = $db->prepare("
            SELECT id, username ,email,password,salt  FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        try {

            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);

        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    public static function updatePasswordFromEmail($email, $password, $salt){
        $db = static::getDB();

        $stmt = $db->prepare('UPDATE users SET password = :password, salt = :salt WHERE email = :email');

        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':salt', $salt);
        $stmt->bindParam(':email', $email);

        try{
            $stmt->execute();
            return true;
        } catch(\Exception $e){
            //Todo : Exception

        }
        return false;
    }
}
