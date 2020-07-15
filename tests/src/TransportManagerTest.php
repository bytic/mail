<?php

namespace Nip\Mail\Tests;

use Mockery\Mock;
use Nip\Mail\Transport\SendgridTransport;
use Nip\Mail\TransportManager;

/**
 * Class TransportManagerTest
 * @package Nip\Mail\Tests
 */
class TransportManagerTest extends AbstractTest
{
    public function test_transport_returnDefaultDriver()
    {
        /** @var TransportManager|Mock $manager */
        $manager = \Mockery::mock(TransportManager::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $manager->shouldReceive('resolve')->with('smtp')->once()->andReturn(true);

        self::assertSame($manager->transport(), $manager->transport());
    }

    public function test_createSendgridTransport()
    {
        $manager = new TransportManager();
        $this->loadConfiguration();

        $sendgrid = $manager->transport('sendgrid');
        self::assertInstanceOf(SendgridTransport::class, $sendgrid);
    }
}