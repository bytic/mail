<?php

namespace Nip\Mail\Models\MergeTags;

/**
 * Trait RecordTrait
 * @package Nip\Mail\Models\MergeTags
 */
trait RecordTrait
{
    protected $mergeTagsDbField = 'vars';

    /**
     * @var array
     */
    protected $mergeTags = null;

    /**
     * @param string $key
     * @return string
     */
    public static function encapsulate($key)
    {
        return '{{' . $key . '}}';
    }

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
        if (method_exists($this,'getPropertyRaw')) {
            return $this->getPropertyRaw($field);
        }
        if (property_exists($this, $field)) {
            return $this->{$field};
        }
        return null;
    }
}
