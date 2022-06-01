<?php


namespace App\Utility;


class Regex
{
    public static function regexAntiScript($data){
        $regex_for_text =
            '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
            'script[^>]*>';
        $correction_text = preg_replace("#$regex_for_text#i",'',$data);
        return $correction_text;
    }
    public static function regexEmail($data){
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $email = (preg_match($regex, $data))?$data:"invalid email";
        return $email;
    }

}