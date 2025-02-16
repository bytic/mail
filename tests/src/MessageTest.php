<?php

namespace Nip\Mail\Tests;

use Nip\Mail\Message;
use Nip\Mail\Utility\Address;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testAddTo()
    {
        $message = new Message();
        $tos = [
            'FName1 LName1 <email_1@yahoo.com>',
            'FName2 LName2 <email_2@yahoo.com>',
            'FName3 LName3 <email_1@yahoo.com>',
        ];
        $message->addTo(...Address::fromArray($tos));
        $tosMessage = $message->getTo();
        self::assertCount(3, $tosMessage);
    }
}
