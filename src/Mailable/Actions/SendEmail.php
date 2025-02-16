<?php
declare(strict_types=1);

namespace Nip\Mail\Mailable\Actions;

use Bytic\Actions\Action;
use Nip\Mail\Message;
use Symfony\Component\Mailer\MailerInterface;

/**
 *
 */
class SendEmail extends Action
{
    protected MailerInterface $mailer;

    protected Message $originalMessage;

    public function handle(MailerInterface $mailer, Message $originalMessage)
    {
        $this->mailer = $mailer;
        $this->originalMessage = $originalMessage;
        $this->execute();
    }

    protected function execute()
    {
        $messages = $this->expandMessages($this->originalMessage);
        foreach ($messages as $message) {
            $this->send($message);
        }
    }

    protected function send(Message $message)
    {
        $this->mailer->send($message);
    }

    protected function expandMessages(Message $message): array
    {
        $emailsTos = $message->getTo();
        $messages = [];
        $index = 0;
        $mergeTags = $message->getMergeTags();

        foreach ($emailsTos as $emailTo) {
            $newMessage = $this->newMessage($message);
            $newMessage->addTo($emailTo);
            $this->replaceSubstitutions($newMessage, $mergeTags, $index);
            $messages[$index] = $newMessage;
            $index++;
        }

        return $messages;
    }

    /**
     * @param $message
     * @return Message
     */
    protected function newMessage(Message $originalMessage): Message
    {
        $message = new Message();
        $message->from(...$originalMessage->getFrom());
        $message->replyTo(...$originalMessage->getReplyTo());
        $message->bcc(...$originalMessage->getBcc());
        $message->subject((string) $originalMessage->getSubject());

        $html = $originalMessage->getHtmlBody();
        $text = $originalMessage->getTextBody();
        if (empty($html) && empty($text)) {
            throw new \InvalidArgumentException('Message must have at least a text or html body');
        }
        $message->html($html);
        $message->text($text);

        $attachments = $originalMessage->getAttachments();
        foreach ($attachments as $attachment) {
            $message->addPart($attachment);
        }
        $message->setCustomArgs($originalMessage->getCustomArgs());
        return $message;
    }

    protected function replaceSubstitutions(Message $newMessage, array $mergeTags, int $index)
    {
        $substitutions = $this->makeSubstitutions($mergeTags, $index);
        $newMessage->subject(
            strtr($newMessage->getSubject(), $substitutions)
        );

        $html = $newMessage->getHtmlBody();
        if (is_string($html)) {
            $html = strtr($html, $substitutions);
        }
        $newMessage->html($html);

        $text = $newMessage->getTextBody();
        if (is_string($text)) {
            $text = strtr($text, $substitutions);
        }
        $newMessage->text($text);
    }

    /**
     * @param array $mergeTags
     * @param int $index
     * @return array
     */
    protected function makeSubstitutions(array $mergeTags, int $index)
    {
        $substitutions = [];
        foreach ($mergeTags as $varKey => $value) {
            if (is_array($value)) {
                $value = $value[$index];
            }
            $value = (string)$value;
            $substitutions['{{' . $varKey . '}}'] = $value;
        }
        return $substitutions;
    }

}

