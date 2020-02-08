<?php

namespace Nip\Mail\Tests\Models\MergeTags;

use Nip\Mail\Models\MergeTags\MergeTagsDbEncoder;
use Nip\Mail\Tests\AbstractTest;

/**
 * Class MergeTagsDbEncoderTest
 * @package Nip\Mail\Tests\Models\MergeTags
 */
class MergeTagsDbEncoderTest extends AbstractTest
{
    public function testDecodeWithSerialize()
    {
        $data = ['fo', ['abc' => 'qwe', 3 => 'asd', 'zxc'], 'fgh' => 'vbn'];

        $serialized = serialize($data);
        self::assertSame($data, MergeTagsDbEncoder::decode($serialized));
    }

    public function testDecodeWithJson()
    {
        $data = ['fo', ['abc' => 'qwe', 3 => 'asd', 'zxc'], 'fgh' => 'vbn'];

        $serialized = json_encode($data);
        self::assertSame($data, MergeTagsDbEncoder::decode($serialized));
    }
}
