<?php

namespace Nip\Mail\Tests;

use Nip\Mail\MailServiceProvider;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;

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

        static::assertInstanceOf(MailerInterface::class, $provider->getContainer()->get('mailer'));
        static::assertInstanceOf(TransportInterface::class, $provider->getContainer()->get('mailer.transport'));
    }
}
