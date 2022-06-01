<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * City Model:
 */
class Cities extends Model {

    public static function search($str) {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT ville_id FROM villes_france WHERE ville_nom_reel LIKE :query');

        $query = $str . '%';

        $stmt->bindParam(':query', $query);
        try{
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
        } catch(\Exception $e){

            echo "<script>console.log('Debug Objects: " . $e . "' );</script>";

        }
    }

    public static function getLongitude($city){
        $db = static::getDB();
        $sql = 'SELECT ville_longitude_deg FROM villes_france WHERE ville_nom_reel =:city';
        try {
            $request = $db->prepare($sql);
            $request->execute((['city'=>$city]));
            $result = $request->fetchAll();
            $longitude = $result[0];
            return $longitude[0];
        } catch (\Exception $e){
            echo $e;
        }
    }

    public static function getLatitude($city){
        $db = static::getDB();
        $sql = 'SELECT ville_latitude_deg FROM villes_france WHERE ville_nom_reel =:city';
        try {
            $request = $db->prepare($sql);
            $request->execute((['city'=>$city]));
            $result = $request->fetchAll();
            $latitude = $result[0];
            return $latitude[0];
        } catch (\Exception $e){
            echo $e;
        }
    }

    public static function getAllCitiesAroundMe($city){
        $db = static::getDB();
        $longitude = self::getLongitude($city);
        $latitude = self::getLatitude($city);
        $sql = "SELECT ville_nom_reel FROM villes_france WHERE (6371 * acos(cos(radians('$latitude')) * cos(radians(ville_latitude_deg)) * cos(radians(ville_longitude_deg) - radians('$longitude')) +sin(radians('$latitude')) * sin(radians(ville_latitude_deg)))) < 15";
        try {
            $request = $db->prepare($sql);
            $request->execute();
            return $request->fetchAll();
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
