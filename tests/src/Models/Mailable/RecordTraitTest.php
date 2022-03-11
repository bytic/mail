<?php
declare(strict_types=1);

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

    public function test_body_get_setters()
    {
        $email = new Email();
        $email->writeData([
            'body' => '{{var1}}{{var2}}',
            'merge_tags' => [
                'var1' => 1,
                'var2' => 2,
            ],
        ]);

        self::assertSame('{{var1}}{{var2}}', $email->getBody());
    }

    public function test_buildMailMessageRecipients()
    {
        $email = new Email();
        $email->writeData([
            'to' => 'test1@gmail.com, test2@gmail.com'
        ]);

        $message = $email->newMailMessage();
        $email->buildMailMessageRecipients($message);

        $tos = $message->getTo();
        self::assertCount(2, $tos);
        self::assertSame('test2@gmail.com', $tos[1]->getAddress());
    }
}
