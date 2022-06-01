<?php


namespace App\Utility;


class Cookie
{
    public static function setCookies($data, $username, $id)
    {
        setcookie('visitorLogged',true,time()+ 60 * 60 * 24);
        setcookie("email",$data,time()+ 60 * 60 * 24);
        setcookie("username",$username,time()+ 60 * 60 * 24);
        setcookie("id",$id,time()+86400);

    }
    public static function delCookies()
    {
        unset($_COOKIE["visitorLogged"]);
        setcookie('visitorLogged',true,time() - 3600);
        unset($_COOKIE["email"]);
        setcookie("email","",time() - 3600);
        unset($_COOKIE["username"]);
        setcookie("username","",time() - 3600);
        unset($_COOKIE["id"]);
        setcookie("id","",time() - 3600);



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