<?php
namespace Chanshige\WhoisProxy;

use PHPUnit\Framework\TestCase;

/**
 * Class CommonTestCase
 *
 * @package Chanshige\WhoisProxy
 */
abstract class CommonTestCase extends TestCase
{
    protected $expected;

    protected $actual;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @param string $msg
     */
    protected function verify($msg = '')
    {
        $this->assertEquals($this->expected, $this->actual, $msg);
    }
}
