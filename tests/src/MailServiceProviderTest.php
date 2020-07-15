<?php

namespace Nip\Mail\Tests;

use Nip\Config\Config;
use Nip\Mail\Mailer;
use Nip\Mail\MailServiceProvider;
use Nip\Mail\Transport\AbstractTransport;
use Swift_Transport;

/**
 * Class MailServiceProviderTest
 * @package Nip\Mail\Tests
 */
class MailServiceProviderTest extends AbstractTest
{
    public function testRegister()
    {
        $provider = new MailServiceProvider();
        $provider->initContainer();
        $provider->register();

        $this->loadConfiguration();

        static::assertInstanceOf(Mailer::class, $provider->getContainer()->get('mailer'));
        static::assertInstanceOf(Swift_Transport::class, $provider->getContainer()->get('mailer.transport'));
    }
}
