<?php

namespace Nip\Mail\Tests\Fixtures\Models\Emails;

use Nip\Mail\Models\Mailable\RecordTrait;

/**
 * Class Email
 * @package Nip\Mail\Tests\Fixtures\Models\Emails
 */
class Email
{
    use RecordTrait;

    protected $body = '';
    protected $mergeTags = [];

    /**
     * @inheritDoc
     */
    public function getFrom()
    {
        // TODO: Implement getFrom() method.
    }

    /**
     * @inheritDoc
     */
    public function getSubject()
    {
        // TODO: Implement getSubject() method.
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function getMergeTags()
    {
        return $this->mergeTags;
    }

    /**
     * @param array $mergeTags
     */
    public function setMergeTags(array $mergeTags)
    {
        $this->mergeTags = $mergeTags;
    }

    /**
     * @inheritDoc
     */
    protected function getCustomArgs()
    {
        // TODO: Implement getCustomArgs() method.
    }

    /**
     * @inheritDoc
     */
    public function getTos()
    {
        // TODO: Implement getTos() method.
    }
}
