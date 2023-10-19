<?php

namespace Nip\Mail\Traits;

use Nip\Mail\Mailable\Actions\SendEmail;
use Nip\Mail\Message;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;

/**
 * Class MailableTrait.
 */
trait MailableTrait
{
    use MailerAwareTrait;

    /**
     * @return void
     */
    public function send()
    {
        $mailer = $this->getMailer();
        $message = $this->buildMailMessage();

        $this->beforeSend($mailer, $message);
        try {
            SendEmail::run($mailer, $message);
            $this->afterSend($mailer, $message);
        } catch (TransportExceptionInterface $e) {
        }
    }

    /**
     * @return Message
     */
    public function buildMailMessage()
    {
        $message = $this->newMailMessage();
        $this->buildMailMessageFrom($message);
        $this->buildMailMessageRecipients($message);
        $this->buildMailMessageSubject($message);
        $this->buildMailMessageBody($message);
        $this->buildMailMessageAttachments($message);
        $this->buildMailMessageMergeTags($message);
        $this->buildMailMessageCustomArgs($message);

        return $message;
    }

    /**
     * @return Message
     */
    public function newMailMessage()
    {
        $message = new Message();

        return $message;
    }

    /**
     * @param Mailer $mailer
     * @param Message $message
     */
    protected function beforeSend($mailer, $message)
    {
    }

    /**
     * @param Mailer $mailer
     * @param Message $message
     * @param int $recipients
     */
    protected function afterSend($mailer, $message)
    {
    }

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageFrom(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageRecipients(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageSubject(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageBody(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageAttachments(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageMergeTags(&$message);

    /**
     * @param Message $message
     */
    abstract public function buildMailMessageCustomArgs(&$message);
}
