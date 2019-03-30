<?php
namespace Chanshige\WhoisProxy;

use Slim\App;

/**
 * Class DependenciesTest
 *
 * @package Chanshige\WhoisProxy
 */
class DependenciesTest extends CommonTestCase
{
    use MockApp;

    /** @var App */
    private $app;

    public function setUp()
    {
        parent::setUp();
        $this->app = $this->getApp();
    }

    public function testDependencies()
    {
        $container = $this->app->getContainer();

        $this->assertInstanceOf(
            '\Chanshige\WhoisProxy\Handler\NotAllowedHandler',
            $container->get('notAllowedHandler')
        );

        $this->assertInstanceOf(
            '\Chanshige\WhoisProxy\Handler\BadRequestHandler',
            $container->get('errorHandler')
        );

        $this->assertInstanceOf(
            '\Chanshige\WhoisProxy\Handler\ApiErrorHandler',
            $container->get('phpErrorHandler')
        );

        $this->assertInstanceOf(
            '\Chanshige\WhoisProxy\Handler\ApiErrorHandler',
            $container->get('notFoundHandler')
        );

        $this->assertInstanceOf(
            '\Monolog\Logger',
            $container->get('logger')
        );

        $this->assertInstanceOf(
            '\Chanshige\Whois',
            $container->get('whois')
        );
    }
}
