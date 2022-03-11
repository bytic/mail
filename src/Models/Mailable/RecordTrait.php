<?php
declare(strict_types=1);

namespace Nip\Mail\Models\Mailable;

use Nip\Mail\Message;
use Nip\Mail\Traits\MailableTrait;
use Nip\Mail\Utility\Address;

/**
 * Class RecordTrait.
 */
trait RecordTrait
{
    use MailableTrait;

    /**
     * @param Message $message
     */
    public function buildMailMessageFrom(&$message)
    {
        $message->setFrom($this->getFrom());
    }

    /**
     * @return string
     */
    abstract public function getFrom();

    /**
     * @param Message $message
     */
    public function buildMailMessageRecipients(&$message)
    {
        foreach (['to', 'cc', 'bcc', 'replyTo'] as $type) {
            $method = 'get' . ucfirst($type) . 's';
            $recipients = method_exists($this, $method) ? $this->{$method}() : $this->{$type};
            if (is_array($recipients)) {
                $message->{'add' . ucfirst($type)}(...Address::fromArray($recipients));
            }
        }
    }

    /**
     * @param Message $message
     */
    public function buildMailMessageSubject(&$message)
    {
        $message->subject((string) $this->getSubject());
    }

    /**
     * @return ?string
     */
    abstract public function getSubject(): ?string;

    /**
     * @param Message $message
     */
    public function buildMailMessageBody(&$message)
    {
        $message->setBody($this->getBody(), 'text/html');
    }

    /**
     * @return string
     */
    abstract public function getBody();

    /**
     * @param Message $message
     */
    public function buildMailMessageAttachments(&$message)
    {
    }

    /**
     * @param Message $message
     */
    public function buildMailMessageMergeTags(&$message)
    {
        $mergeTags = $this->getMergeTags();
        $body = $this->getBody();
        foreach ($mergeTags as $tag => $value) {
            $encapsulated = \Nip\Mail\Models\MergeTags\RecordTrait::encapsulate($tag);
            if (false !== strpos($body, $encapsulated)) {
                $message->addMergeTag($tag, $value);
            }
        }
    }

    /**
     * @return array
     */
    abstract public function getMergeTags();

    /**
     * @param Message $message
     */
    public function buildMailMessageCustomArgs(&$message)
    {
        $message->setCustomArgs($this->getCustomArgs());
    }

    /**
     * @return array
     */
    abstract protected function getCustomArgs();

    /**
     * @return string
     */
    abstract public function getTos();
}
