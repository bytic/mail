<?php

namespace Nip\Mail;

use Swift_Message;

/**
 * Class Message
 * @package Nip\Mail
 */
class Message extends Swift_Message
{
    protected $mergeTags = [];

    protected $customArgs = [];

    /**
     * @return array
     */
    public function getMergeTags()
    {
        return $this->mergeTags;
    }

    /**
     * @param array $mergeTags
     */
    public function setMergeTags($mergeTags)
    {
        $this->mergeTags = $mergeTags;
    }

    /**
     * @param $name
     * @param $value
     */
    public function addMergeTag($name, $value)
    {
        $this->mergeTags[$name] = $value;
    }

    /**
     * @return array
     */
    public function getCustomArgs()
    {
        return $this->customArgs;
    }

    /**
     * @param array $customArgs
     */
    public function setCustomArgs($customArgs)
    {
        $this->customArgs = $customArgs;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addCustomArg($key, $value)
    {
        $this->customArgs[$key] = $value;
    }
}
