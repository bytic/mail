<?php

namespace Nip\Mail\Tests\MessageBuilder;

use Nip\Mail\MessageBuilder;
use Nip\Mail\Tests\AbstractTest;

class MessageProxyTest extends AbstractTest
{
    public function testProxyFrom()
    {
        $builder = new MessageBuilder();
        $builder->from('solomongab@yahoo.com');

        $message = $builder->getMessage();
        self::assertSame('solomongab@yahoo.com', $message->getFrom()[0]->toString());
    }

    public function testProxyAddTo()
    {
        $builder = new MessageBuilder();
        $builder->addTo('solomongab@yahoo.com', 'Solomon');

        $message = $builder->getMessage();
        self::assertSame('"Solomon" <solomongab@yahoo.com>', $message->getTo()[0]->toString());
    }
}
