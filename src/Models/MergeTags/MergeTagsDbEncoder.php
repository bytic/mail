<?php

namespace Nip\Mail\Models\MergeTags;

use Nip\Utility\Str;
use Symfony\Component\Translation\Exception\InvalidResourceException;

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
     * @param string $data
     * @return mixed
     */
    public static function decode($data)
    {
        if (empty($data)) {
            return [];
        }

        if (Str::isSerialized($data)) {
            return unserialize($data);
        }
        $data = json_decode($data, true);

        if (0 < $errorCode = json_last_error()) {
            throw new InvalidResourceException('Error parsing JSON: ' . static::getJSONErrorMessage($errorCode));
        }
        return $data;
    }

    /**
     * Translates JSON_ERROR_* constant into meaningful message.
     * @param int $errorCode
     * @return string
     */
    protected static function getJSONErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}
