<?php

namespace Nip\Mail\Models\MergeTags;

/**
 * Class RecordTrait
 * @package Nip\Mail\Models\MergeTags
 */
class RecordTrait
{
    protected $mergeTagsDbField = 'vars';

    /**
     * @var array
     */
    protected $mergeTags = null;

    /**
     * @return array|null
     */
    public function getMergeTags()
    {
        if ($this->mergeTags === null) {
            $this->initMergeTags();
        }

        return $this->mergeTags;
    }

    /**
     * @param array $mergeTags
     */
    public function setMergeTags($mergeTags)
    {
        $this->mergeTags = $mergeTags;
    }

    protected function initMergeTags()
    {
        $mergeTags = $this->generateMergeTags();
        $this->setMergeTags($mergeTags);
    }

    /**
     * @return mixed
     */
    protected function generateMergeTags()
    {
        return MergeTagsDbEncoder::decode($this->getMergeTagsDbFieldValue());
    }

    protected function saveMergeTagsToDbField()
    {
        $field = $this->mergeTagsDbField;
        $this->{$field} = MergeTagsDbEncoder::encode($this->mergeTags);
    }

    /**
     * @return mixed
     */
    protected function getMergeTagsDbFieldValue()
    {
        $field = $this->mergeTagsDbField;
        return isset($this->{$field}) ? $this->{$field} : null;
    }
}
