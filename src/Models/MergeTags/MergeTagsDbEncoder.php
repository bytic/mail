<?php

namespace Nip\Mail\Models\MergeTags;

use Nip\Utility\Str;

/**
 * Class MergeTagsDbEncoder
 * @package Nip\Mail\Models\MergeTags
 */
class MergeTagsDbEncoder
{
    /**
     * @param $value
     * @return false|string
     */
    public static function encode($value)
    {
        return json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function decode($value)
    {
        if (Str::isSerialized($value)) {
            return unserialize($value);
        }
        return json_decode($value, true);
    }
}
