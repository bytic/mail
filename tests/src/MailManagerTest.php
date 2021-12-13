<?php

namespace Nip\Mail\Tests;

use Mockery\Mock;
use Nip\Mail\MailerManager;
use Nip\Mail\Transport\TransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\NullTransport;

/**
 * Class TransportManagerTest.
 */
class MailManagerTest extends AbstractTest
{
    public function test_transportReturnDefaultDriver()
    {
        /** @var TransportFactory|Mock $manager */
        $manager = \Mockery::mock(MailerManager::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $manager->shouldReceive('getDefaultDriver')->andReturn('sendgrid');
        $manager->shouldReceive('resolve')->with('sendgrid')->once()->andReturn(new Mailer(new NullTransport()));

        self::assertSame($manager->mailer(), $manager->mailer());
    }

    public function testCreateSendgridTransport()
    {
        $manager = new MailerManager();
        $this->loadConfiguration();

        $sendgrid = $manager->mailer('sendgrid');
        self::assertInstanceOf(Mailer::class, $sendgrid);
    }
}
