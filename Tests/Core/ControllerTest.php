<?php

namespace Tests\Core;

use Core\Controller;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{

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

    public function testCallMethodExist() {
        $routeParamsTest = self::getProtectedVar('route_params');
        $actionName = 'Action';
        $args = [];

        $controllerTest = $this->getMockBuilder(Controller::class)
            ->onlyMethods(['__call'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->assertNotEmpty($controllerTest);
    }
}