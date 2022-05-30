<?php

namespace Tests\Core;

use App\Controllers\User;
use App\Utility\Hash;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    //Test de la fonction register (inscription)
    //Test pour vérifier s'il détecte bien les deux mots de passe identique
    //Vérifie également si le mot de passe généré et en BDD sont égaux
    public function testregisterAction()
    {
        $_POST['submit']="submit";
        $_POST['password'] = "passwd";
        $_POST['password-check'] = "passwd1";
        $data= null;
        $toto = new User($data);
        $client = $toto->register();
        $this->assertEquals($data,$client);

    }
    public function testlogoutAction()
    {

    }
}