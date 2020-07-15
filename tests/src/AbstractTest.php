<?php

namespace Nip\Mail\Tests;

use Nip\Config\Config;
use Nip\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{
    protected $object;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
        Container::getInstance()->set('config', null);
    }

    /**
     * @param $name
     */
    protected function loadConfiguration($name = 'mail')
    {
        /** @noinspection PhpIncludeInspection */
        $config = new Config(['mail' => require TEST_FIXTURE_PATH . '/config/' . $name . '.php']);
        Container::getInstance()->set('config', $config);
    }
}
