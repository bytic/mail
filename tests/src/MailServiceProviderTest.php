<?php

namespace Nip\Mail\Tests;

use Nip\Mail\Mailer;
use Nip\Mail\MailServiceProvider;
use Nip\Mail\Transport\AbstractTransport;

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

        static::assertInstanceOf(Mailer::class, $provider->getContainer()->get('mailer'));
        static::assertInstanceOf(AbstractTransport::class, $provider->getContainer()->get('mailer.transport'));
    }
}
