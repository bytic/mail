<?php

namespace Nip\Mail\Tests;

use Mockery\Mock;
use Nip\Mail\TransportManager;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;

/**
 * Class TransportManagerTest.
 */
class TransportManagerTest extends AbstractTest
{
    public function testTransportReturnDefaultDriver()
    {
        /** @var TransportManager|Mock $manager */
        $manager = \Mockery::mock(TransportManager::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $manager->shouldReceive('getDefaultDriver')->andReturn('sendgrid');
        $manager->shouldReceive('resolve')->with('sendgrid')->once()->andReturn(true);

        self::assertSame($manager->transport(), $manager->transport());
    }

    public function testCreateSendgridTransport()
    {
        $manager = new TransportManager();
        $this->loadConfiguration();

        $sendgrid = $manager->transport('sendgrid');
        self::assertInstanceOf(SendgridApiTransport::class, $sendgrid);
    }
}
