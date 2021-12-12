<?php

namespace Nip\Mail\Tests\Message;

use Nip\Mail\Message;
use Nip\Mail\Tests\AbstractTest;

/**
 * Class HasAttachmentsTraitTest.
 */
class HasAttachmentsTraitTest extends AbstractTest
{
    public function testAttachFromContent()
    {
        $message = new Message();
        $message->attachFromContent('test');
        static::assertCount(1, $message->getAttachments());

        $message->attachFromContent('test');
        static::assertCount(2, $message->getAttachments());
    }
}
