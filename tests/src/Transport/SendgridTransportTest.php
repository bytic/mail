<?php

namespace Nip\Mail\Tests\Transport;

use Mockery\Mock;
use Nip\Mail\Message;
use Nip\Mail\Tests\AbstractTest;
use Nip\Mail\Transport\SendgridTransport;
use SendGrid;
use Swift_Mime_SimpleMessage as SwiftSimpleMessage;

/**
 * Class SendgridTransportTest
 * @package Nip\Mail\Tests\Transport
 */
class SendgridTransportTest extends AbstractTest
{
    public function test_send()
    {
        $message = new Message();

        $message->setFrom('from@bytic.com');
        $message->addTo('recipient@bytic.com');
        $message->setSubject('Subject');

        /** @var Mock|SendgridTransport $transport */
        $transport = \Mockery::mock(SendgridTransport::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $transport->shouldReceive('sendApiCall')->andReturn(1);

        self::assertSame(1, $transport->send($message));

        $mail = $transport->getMail();
        self::assertInstanceOf(SendGrid\Mail\Mail::class, $mail);
    }
}
