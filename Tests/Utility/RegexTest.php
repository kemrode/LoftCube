<?php

namespace Tests\Utility;

use App\Utility\Regex;
use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
{
    public function testregexAntiScriptok()
    {
        $string = "test";
        $regex_for_text =
            '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
            'script[^>]*>';
        $test = preg_replace("#$regex_for_text#i",'',$string);

        $var = new Regex();
        $client = $var->regexAntiScript($string);
        $this->assertEquals($client,$test);

    }
    public function testregexAntiScriptpasok()
    {
        $string = "<script> alert('test') </script>";
        $regex_for_text =
            '<[\n\r\s]*script[^>]*[\n\r\s]*(type\s?=\s?"text/javascript")*>.*?<[\n\r\s]*/' .
            'script[^>]*>';
        $test = preg_replace("#$regex_for_text#i",'',$string);

        $var = new Regex();
        $client = $var->regexAntiScript($string);
        $this->assertEquals($client,$test);

    }

    public function testEmailOk()
    {
        $string = "test@test.fr";
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $test = (preg_match($regex, $string))?$string:"invalid email";
        $var = new Regex();
        $client = $var->regexEmail($string);
        $this->assertEquals($client,$test);

    }
    public function testEmailPasOk()
    {
        $string = "testtest.fr";
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $test = (preg_match($regex, $string))?$string:"invalid email";
        $var = new Regex();
        $client = $var->regexEmail($string);
        $this->assertEquals($client,$test);

    }
}
