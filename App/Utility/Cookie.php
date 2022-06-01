<?php


namespace App\Utility;


class Cookie
{
    public static function setCookies($data , $string)
    {
        setcookie('visitorLogged',true,time()+86400);
        setcookie("email",$data,time()+86400);
        setcookie("password",$string,time()+86400);

    }
    public static function delCookies($data , $string)
    {
        setcookie('visitorLogged',true,time()+86400);
        setcookie("email",$data,time()+86400);
        setcookie("password",$string,time()+86400);

    }
    public static function delCookies2()
{
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );

}

}