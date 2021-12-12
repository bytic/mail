<?php

namespace Nip\Mail\Tests\Models\Mailable;

use Nip\Mail\Tests\AbstractTest;
use Nip\Mail\Tests\Fixtures\Models\Emails\Email;

/**
 * Class RecordTraitTest.
 */
class RecordTraitTest extends AbstractTest
{
    public function testBuildMailMessageMergeTagsStripNotPresent()
    {
        $email = new Email();
        $email->setBody('{{var1}}{{var3}}');
        $email->setMergeTags(['var1' => 1, 'var2' => 2, 'var3' => 3]);

        $message = $email->newMailMessage();
        $email->buildMailMessageMergeTags($message);

        $tags = $message->getMergeTags();
        self::assertCount(2, $tags);
    }
}
