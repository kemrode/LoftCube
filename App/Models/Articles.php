<?php

namespace App\Models;

use Core\Model;
use App\Core;
use DateTime;
use Exception;
use App\Utility;

/**
 * Articles Model
 */
class Articles extends Model {

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getAll($filter) {
        $db = static::getDB();

        $query = 'SELECT * FROM articles ';

        switch ($filter){
            case 'views':
                $query .= ' ORDER BY articles.views DESC';
                break;
            case 'data':
                $query .= ' ORDER BY articles.published_date DESC';
                break;
            case '':
                break;
        }
        try{
            $stmt = $db->query($query);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getOne($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT * FROM articles
            INNER JOIN users ON articles.user_id = users.id
            WHERE articles.id = ? 
            LIMIT 1');
        try{
            $stmt->execute([$id]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function addOneView($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            UPDATE articles 
            SET articles.views = articles.views + 1
            WHERE articles.id = ?');
        try{
            $stmt->execute([$id]);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getByUser($id) {
        $db = static::getDB();
        $query = '
            SELECT *, articles.id as id FROM articles
            LEFT JOIN users ON articles.user_id = users.id
            WHERE articles.user_id = ? ';
        if (isset($_GET['arg'])){
            if ($_GET['arg'] == 'pop'){
                $query .= 'order by views desc';
            }
            if ($_GET['arg'] == 'rec'){
                $query .= 'order by published_date desc';

            }
        }
        
        $stmt = $db->prepare($query);
        try{
            $stmt->execute([$id]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }
    public static function getcountByUser($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
        SELECT COUNT(*) as nb_art FROM articles WHERE user_id = ?');
        try{
            $stmt->execute([$id]);
            $e = $stmt->fetch(\PDO::FETCH_ASSOC);


            return $e;
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }
    public static function getcountviewByUser($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
        SELECT SUM(views) as nb_vue FROM articles WHERE user_id = ?');
        try{
            $stmt->execute([$id]);
            $e = $stmt->fetch(\PDO::FETCH_ASSOC);


            return $e;
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getSuggest() {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            INNER JOIN users ON articles.user_id = users.id
            ORDER BY published_date DESC LIMIT 10');
        try{
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }



    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function save($data) {
        $regex_for_text =
            '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
            'script[^>]*>';
        $data['name'] = preg_replace("#$regex_for_text#i",'',$data['name']);
        $data['description'] = preg_replace("#$regex_for_text#i",'',$data['description']);
        $data['city'] = preg_replace("#$regex_for_text#i",'',$data['city']);

      if ( isset($data['name']) && isset($data['description']) && isset($data['user_id']) && isset($data['city'])
          && $data['name']!="" && $data['description']!="" && $data['city']!="" && $data['user_id']!="") {
          $db = static::getDB();
          $stmt = $db->prepare('INSERT INTO articles(name, description, user_id,city, published_date) VALUES (:name, :description, :user_id,:city,:published_date)');
          $published_date = new DateTime();
          $published_date = $published_date->format('Y-m-d');
          $stmt->bindParam(':name', $data['name']);
          $stmt->bindParam(':description', $data['description']);
          $stmt->bindParam(':published_date', $published_date);
          $stmt->bindParam(':user_id', $data['user_id']);
          $stmt->bindParam(':city', $data['city']);
          try {
              $stmt->execute();

              return $db->lastInsertId();
          } catch (\Exception $e) {

              echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

          }
      }else{

      }
    }

    public static function attachPicture($articleId, $pictureName){
        $db = static::getDB();

        $stmt = $db->prepare('UPDATE articles SET picture = :picture WHERE articles.id = :articleid');

        $stmt->bindParam(':picture', $pictureName);
        $stmt->bindParam(':articleid', $articleId);

        try{
            $stmt->execute();
        } catch(\Exception $e){
            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
        }
    }

    public static function searchByWording($object) {
        $db = static::getDB();
        $sql = "SELECT * FROM articles WHERE name LIKE :name OR description LIKE :description";
        try {
            $request = $db->prepare($sql);
            $request->execute([
                'name' => '%'.$object.'%',
                'description' => '%'.$object.'%'
            ]);
            return $request->fetchAll();
        } catch (\Exception $e) {
            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";
        }
    }

    public static function searchAroundMe($city){
        $db = static::getDB();
        $cityLongitude = self::getCityLongitude($city);
        $cityLatitude = self::getCityLatitude($city);
        $test = $cityLongitude[0];
        $longitude = $test[0];
        $testLat = $cityLatitude[0];
        $latitude = $testLat[0];
        $sql = "SELECT ville_nom_reel FROM villes_france WHERE (6371 * acos(cos(radians('$latitude')) * cos(radians(ville_latitude_deg)) * cos(radians(ville_longitude_deg) - radians('$longitude')) +sin(radians('$latitude')) * sin(radians(ville_latitude_deg)))) < 15";
        try {
            $request = $db->prepare($sql);
            $request->execute();
            return $request->fetchAll();
        } catch (\Exception $e) {
            echo $e;
        }

//        $sql = "SELECT *,
//       (acos(cos(radians('.$lat')) * cos(radians(lat)) * cos(radians(long) - radians('.$long')) + sin(radians('.$lat')) * sin(radians(lat))))
//           AS distance FROM articles WHERE distance < 15 ORDER BY distance LIMIT 0, 10";
////        $sql = "SELECT * FROM articles WHERE city =:city";
//        try {
//            $request = $db->prepare($sql);
//            $request->execute(['city'=>$city]);
//            return $request->fetchAll();
//        } catch (\Exception $e) {
//            echo $e;
//        }
    }

    public static function getCityLongitude($city){
        $db = static::getDB();
//        $upperCity = strtoupper($city);
        $sql = 'SELECT ville_longitude_deg FROM villes_france WHERE ville_nom_reel =:city';
        try {
            $request = $db->prepare($sql);
            $request->execute((['city'=>$city]));
            return $request->fetchAll();
        } catch (\Exception $e){
            echo $e;
        }
    }
    public static function getCityLatitude($city){
        $db = static::getDB();
//        $upperCity = strtoupper($city);
        $sql = 'SELECT ville_latitude_deg FROM villes_france WHERE ville_nom_reel =:city';
        try {
            $request = $db->prepare($sql);
            $request->execute((['city'=>$city]));
            return $request->fetchAll();
        } catch (\Exception $e){
            echo $e;
        }
    }
}
