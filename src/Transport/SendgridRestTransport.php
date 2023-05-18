<?php
declare(strict_types=1);

namespace Nip\Mail\Transport;

use Exception;
use Html2Text\Html2Text;
use Nip\Mail\Message;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Attachment;
use SendGrid\Mail\Content;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;

/**
 * Class SendgridTransport
 * @package Nip\Mail\Transport
 */
class SendgridRestTransport extends AbstractTransport
{
    /** @var string|null */
    protected ?string $apiKey;

    public function __construct(EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        parent::__construct($dispatcher, $logger);
    }

    /**
     * @return null|string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @throws SendGrid\Mail\TypeException
     */
    protected function doSend(SentMessage $message): void
    {
        $mail = new Mail();

        $message = $message->getOriginalMessage();

        /** @var Message $message */
        $this->populateSenders($message, $mail);
        $this->populatePersonalization($message, $mail);
        $this->populateContent($message, $mail);
        $this->populateCustomArg($message, $mail);

        $this->sendApiCall($mail);
    }


    /**
     * @param \Symfony\Component\Mime\Message $message
     * @param Mail $mail
     * @throws SendGrid\Mail\TypeException
     */
    protected function populateSenders(\Symfony\Component\Mime\Message $message, Mail $mail)
    {
        $addresses = $message->getFrom();
        foreach ($addresses as $address) {
            $mail->setFrom($address->getAddress(), $address->getName());
            $mail->setReplyTo($address->getAddress(), $address->getName());
        }

        $reply = $message->getReplyTo();
        foreach ($reply as $address) {
            $mail->setReplyTo($address->getAddress(), $address->getName());
        }


    }

    /**
     * @param Message $message
     * @param Mail $mail
     * @throws SendGrid\Mail\TypeException
     */
    protected function populatePersonalization(Message $message, Mail $mail)
    {
        $emailsTos = $message->getTo();
        $personalizationIndex = 0;
        foreach ($emailsTos as $emailTo) {
            $personalization = $this->generatePersonalization($emailTo, $message, $personalizationIndex);
            $mail->addPersonalization($personalization);
            $personalizationIndex++;
        }
    }

    /**
     * @param Address $emailTo
     * @param Message $message
     * @param integer $i
     * @return Personalization
     * @throws SendGrid\Mail\TypeException
     */
    protected function generatePersonalization(Address $emailTo, Message $message, $i): Personalization
    {
        $personalization = new Personalization();

        $email = new To($emailTo->getAddress(), $emailTo->getName());
        $personalization->addTo($email);

        $bcc = $message->getBcc();
        foreach ($bcc as $address) {
            $email = new SendGrid\Mail\Bcc($address->getAddress(), $address->getName());
            $personalization->addBcc($email);
        }

        $personalization->setSubject($message->getSubject());

        $mergeTags = $message->getMergeTags();
        foreach ($mergeTags as $varKey => $value) {
            if (is_array($value)) {
                $value = $value[$i];
            }
            $value = (string)$value;
            $personalization->addSubstitution('{{' . $varKey . '}}', $value);
        }

        return $personalization;
    }

    /**
     * @param Message $message
     * @param Mail $mail
     * @throws SendGrid\Mail\TypeException
     */
    protected function populateContent(Message $message, Mail $mail)
    {
        foreach ($message->getAttachments() as $attachment) {
            $this->addAttachment($attachment, $mail);
        }
        $bodyHtml = $message->getHtmlBody();
        $bodyText = $message->getTextBody();

        $bodyText = $bodyText ?? (new Html2Text($bodyHtml))->getText();

        $content = new Content("text/plain", $bodyText);
        $mail->addContent($content);

        $content = new Content("text/html", $bodyHtml);
        $mail->addContent($content);
    }

    /**
     * @param DataPart $attachment
     * @param Mail $mail
     * @throws SendGrid\Mail\TypeException
     */
    protected function addAttachment(DataPart $attachment, $mail)
    {
        $headers = $attachment->getPreparedHeaders();
        $filename = $headers->getHeaderParameter('Content-Disposition', 'filename');
        $disposition = $headers->getHeaderBody('Content-Disposition');

        $sgAttachment = new Attachment();
        $sgAttachment->setContent(str_replace("\r\n", '', $attachment->bodyToString()));
        $sgAttachment->setType($headers->get('Content-Type')->getBody());
        $sgAttachment->setFilename($filename);
        $sgAttachment->setDisposition($disposition);

        if ('inline' === $disposition) {
            $sgAttachment->setContentID($filename);
        }

        $mail->addAttachment($sgAttachment);
    }

    /**
     * @param Message $message
     * @param $mail
     */
    protected function populateCustomArg(Message $message, $mail)
    {
        $args = $message->getCustomArgs();
        foreach ($args as $key => $value) {
            if ($key == 'category') {
                $mail->addCategory($value);
            } else {
                $mail->addCustomArg($key, (string)$value);
            }
        }
    }

    /**
     * @return bool
     * @throws TransportException
     */
    protected function sendApiCall($mail): bool
    {
        $sendGrid = $this->createApi();
        try {
            $response = $sendGrid->send($mail);
        } catch (Exception $exception) {
            throw new TransportException(
                'Error sending email Code [' . $exception->getMessage() . ']'
            );
        }

        if ($response->statusCode() == '202') {
            return true;
        } else {
            throw new TransportException(
                'Error sending email Code [' . $response->statusCode() . ']. '
                . 'HEADERS [' . print_r($response->headers())
                . $response->body()
            );
        }
    }

    /**
     * @return SendGrid
     * @throws TransportException
     */
    protected function createApi(): SendGrid
    {
        if ($this->getApiKey() === null) {
            throw new TransportException('Cannot create instance of \SendGrid while API key is NULL');
        }

        return new SendGrid($this->getApiKey());
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
