<?php

namespace Tests\Core;

use Core\Controller;
use Core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected static function haveMethod($name) {
        $router = new \ReflectionClass('Core\Router');
        $removeQueryStringVariables = $router->getMethod($name);
        $removeQueryStringVariables->setAccessible(true);
        return $removeQueryStringVariables;
    }

    protected static function getProtectedVar($name) {
        $router = new \ReflectionClass('Core\Router');
        $protectedVar = $router->getProperty($name);
        $protectedVar->setAccessible(true);
        return $protectedVar;
    }

    public function testAssertRoutesIsNull()
    {
        $routes = [];
        $this->assertEmpty($routes);
        return $routes;
    }

    public function testAdd()
    {
        $route = "App\Controllers\tests";
        $param = [];

        $client = $this->getMockBuilder(Router::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertNotEmpty($client);
    }


    public function testGetRoutes()
    {
        $client = $this->getMockBuilder(Router::class)
            ->onlyMethods(['getRoutes'])
            ->getMock();
        $this->assertNotNull($client);
    }

    public function testGetParams()
    {
        $client = $this->getMockBuilder(Router::class)
            ->onlyMethods(['getParams'])
            ->getMock();
        $this->assertNotEmpty($client);
    }

    public function testMatchFalse(){
        $urlTest = "";
        $routesTest = self::getProtectedVar('routes');
        $paramsTest = self::getProtectedVar('params');
        $matchTest = self::haveMethod('match');
        $this->assertEquals(false, $matchTest->invokeArgs(new Router(), [$routesTest, $paramsTest, $urlTest]));
    }

    public function testMatchTrue(){
        $urlTest = "login";
        $routesTest = self::getProtectedVar('routes');
        $paramsTest = self::getProtectedVar('params');
        $matchTest = self::haveMethod('match');
        $this->assertEquals(true, $matchTest->invokeArgs(new Router(), [$routesTest, $paramsTest, $urlTest]));
    }

    public function testDispatch(){
        $testString = "testq=/string&hope";
        $testResult = "teststring";
        $client = $this->getMockBuilder(Router::class)
            ->onlyMethods(['dispatch'])
            ->getMock();
        $removeQueryStringVariables = self::haveMethod('removeQueryStringVariables');
        $this->assertSame($testResult, $removeQueryStringVariables->invokeArgs(new Router(), [$testString]));
//        $client->expects($this->once())
//            ->method('removeQueryStringVariables')
//            ->with($this->equalTo($testResult));
//        $this->assertSame($testResult, $client);
    }
}