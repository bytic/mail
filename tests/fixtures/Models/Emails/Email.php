<?php

declare(strict_types=1);

namespace Nip\Mail\Tests\Fixtures\Models\Emails;

use Nip\Mail\Models\Mailable\RecordTrait;
use Nip\Records\Record;

/**
 * Class Email.
 */
class Email extends Record
{
    use RecordTrait;

    protected $body = '';
    protected $mergeTags = [];

    /**
     * {@inheritDoc}
     */
    public function getFrom()
    {
        // TODO: Implement getFrom() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject(): ?string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritDoc}
     */
    public function getMergeTags()
    {
        return $this->mergeTags;
    }

    public function setMergeTags(array $mergeTags)
    {
        $this->mergeTags = $mergeTags;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCustomArgs()
    {
        // TODO: Implement getCustomArgs() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getTos()
    {
        return explode(',', $this->get('to'));
    }
}
