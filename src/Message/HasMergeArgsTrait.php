<?php

namespace Nip\Mail\Message;

/**
 * Trait HasMergeArgsTrait.
 */
trait HasMergeArgsTrait
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
