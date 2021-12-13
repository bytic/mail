<?php

namespace Nip\Mail\Tests;

use Nip\Mail\MailServiceProvider;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * Class MailServiceProviderTest.
 */
class MailServiceProviderTest extends AbstractTest
{
    public function testRegister()
    {
        $provider = new MailServiceProvider();
        $provider->initContainer();
        $provider->register();

        $this->loadConfiguration('mail', $provider->getContainer());

        $mailer = $provider->getContainer()->get('mailer');

        static::assertInstanceOf(MailerInterface::class, $mailer);
    }
}
