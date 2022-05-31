<?php

namespace Tests\Utility;

use App\Utility\Hash;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{

    public function testgenerate()
    {
        $string = "test";
        $salt="";
        $test =  hash("sha256", $string . $salt);

        $var = new Hash();
        $client = $var->generate($string);

        $this->assertEquals($client,$test);
    }
    public function testgenerateSalt()
    {
        $lenght= 10;
        $var = new Hash();
        $client = $var->generateSalt($lenght);
        $this->assertEquals(strlen($client),$lenght);

    }
    public function testgenerateUnique()
    {
        $lenght= 64;

        $var = new Hash();
        $client = $var->generateUnique();
        $this->assertEquals(strlen($client),$lenght);

    }
}