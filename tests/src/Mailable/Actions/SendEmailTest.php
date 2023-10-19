<?php
declare(strict_types=1);

namespace Nip\Mail\Tests\Mailable\Actions;

use Nip\Mail\Mailable\Actions\SendEmail;
use Nip\Mail\Tests\AbstractTest;

/**
 *
 */
class SendEmailTest extends AbstractTest
{

    public function testHandle()
    {
        $transport = \Mockery::mock(\Symfony\Component\Mailer\Transport\TransportInterface::class)->makePartial();
        $transport->shouldReceive('send')
            ->with(\Mockery::capture($sentMessage), \Mockery::capture($sentEnvelope))
            ->twice();

        $mailer = new \Symfony\Component\Mailer\Mailer($transport);

        $message = new \Nip\Mail\Message();
        $message->addTo('test1@gmail.com');
        $message->addTo('test2@gmail.com');
        $message->subject('test subject {{tag1}}{{tag2}}');
        $message->html('test html {{tag1}}{{tag2}}');
        $message->text('test text {{tag1}}{{tag2}}');
        $message->setMergeTags(['tag1' => 'value1', 'tag2' => ['value21', 'value22']]);

        $action = new SendEmail();
        $action->handle($mailer, $message);

        self::assertSame('test subject value1value22', $sentMessage->getSubject());
        self::assertSame('test html value1value22', $sentMessage->getHtmlBody());
        self::assertSame('test text value1value22', $sentMessage->getTextBody());
    }
}
