<?php

namespace Nip\Mail\Tests\Message;

use Nip\Mail\Message;
use Nip\Mail\Tests\AbstractTest;

/**
 * Class HasAttachmentsTraitTest
 * @package Nip\Mail\Tests\Message
 */
class HasAttachmentsTraitTest extends AbstractTest
{
    public function test_attachFromContent()
    {
        $message = new Message();
        $message->attachFromContent('test');
        static::assertCount(1, $message->getAttachments());

        $message->attachFromContent('test');
        static::assertCount(2, $message->getAttachments());
    }
}
