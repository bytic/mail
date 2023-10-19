<?php
declare(strict_types=1);

namespace Nip\Mail\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Nip\Config\Config;
use Nip\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected $object;

    public function tearDown(): void
    {
        parent::tearDown();
        $this->mockeryAssertPostConditions();
        Container::getInstance()->set('config', null);
    }

    /**
     * @param $name
     */
    protected function loadConfiguration($name = 'mail', $container = null)
    {
        /** @noinspection PhpIncludeInspection */
        $config = new Config(['mail' => require TEST_FIXTURE_PATH.'/config/'.$name.'.php']);
        $container = $container ?: Container::getInstance();
        $container->set('config', $config);
    }
}
