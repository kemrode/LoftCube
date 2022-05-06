<?php

namespace Tests\Core;

use Core\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{

    public function testsetDefaultVariablesIssetSession()
    {
        $args["user"] = "testSession";
        $_SESSION["user"]= $args["user"];
        $view = new View();
        $client = $view->setDefaultVariables();
        $this->assertEquals($args,$client);
    }
    public function testsetDefaultVariablesNotIssetSession()
    {
        $_SESSION["user"]= null;
        $args["user"]=$_SESSION["user"];

        $view = new View();
        $client = $view->setDefaultVariables();
        $this->assertEquals($args,$client);
    }

}




// $this->assertEquals($test, $client);

//$this->assertEquals(true, $matchTest->invokeArgs(new Router(), [$routesTest, $paramsTest, $urlTest]));
