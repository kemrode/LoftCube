<?php

namespace Tests\Core;

use Core\Controller;
use PHPUnit\Framework\TestCase;

function method_exists(){
    return ControllerTest::$function->method_exists();
}

class ControllerTest extends TestCase
{
    public static $function;


    protected static function getProtectedVar($name) {
        $router = new \ReflectionClass('Core\Controller');
        $protectedVar = $router->getProperty($name);
        $protectedVar->setAccessible(true);
        return $protectedVar;
    }

    protected static function haveMethod($name) {
        $router = new \ReflectionClass('Core\Controller');
        $removeQueryStringVariables = $router->getMethod($name);
        $removeQueryStringVariables->setAccessible(true);
        return $removeQueryStringVariables;
    }

    //Todo : Réparer le test unitaire ?
    public function testCallNotEmpty() {
        $controllerTest = $this->getMockBuilder(Controller::class)
            ->onlyMethods(['__call'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->assertNotEmpty($controllerTest);
    }
}