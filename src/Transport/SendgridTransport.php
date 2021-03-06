<?php

namespace Nip\Mail\Transport;

use Exception;
use Html2Text\Html2Text;
use Nip\Mail\Message;
use ReflectionClass;
use ReflectionException;
use SendGrid;
use SendGrid\Mail\Attachment;
use SendGrid\Mail\Content;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
use Swift_Attachment;
use Swift_Image;
use Swift_Mime_SimpleMessage as SwiftSimpleMessage;
use Swift_MimePart;
use Swift_TransportException;

/**
 * Class SendgridTransport
 * @package Nip\Mail\Transport
 */
class SendgridTransport extends AbstractTransport
{
    /** @var string|null */
    protected $apiKey;

    /**
     * @var null|Mail|SwiftSimpleMessage
     */
    protected $mail = null;

    /**
     * @return null|string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function send(SwiftSimpleMessage $message, &$failedRecipients = null)
    {
        $this->initMail();

        $this->populateSenders($message);
        $this->populatePersonalization($message);
        $this->populateContent($message);
        $this->populateCustomArg($message);

        return $this->sendApiCall();
    }

    public function initMail()
    {
        $this->setMail(new Mail());
    }

    /**
     * @param Message|SwiftSimpleMessage $message
     */
    protected function populateSenders($message)
    {
        $from = $message->getFrom();
        foreach ($from as $address => $name) {
            $this->getMail()->setFrom($address, $name);
            $this->getMail()->setReplyTo($address, $name);
        }
        $reply = $message->getReplyTo();
        if (is_array($reply)) {
            foreach ($reply as $address => $name) {
                $this->getMail()->setReplyTo($address, $name);
            }
        }
    }

    /**
     * @return null|Mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param null|Mail $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @param Message|SwiftSimpleMessage $message
     * @throws Exception
     */
    protected function populatePersonalization($message)
    {
        $emailsTos = $message->getTo();
        if (!is_array($emailsTos) or count($emailsTos) < 1) {
            throw new Exception('Cannot send email withought reciepients');
        }
        $personalizationIndex = 0;
        foreach ($emailsTos as $emailTo => $nameTo) {
            $personalization = $this->generatePersonalization($emailTo, $nameTo, $message, $personalizationIndex);
            $this->getMail()->addPersonalization($personalization);
            $personalizationIndex++;
        }
    }

    /**
     * @param $emailTo
     * @param $nameTo
     * @param Message $message
     * @param integer $i
     * @return Personalization
     * @throws SendGrid\Mail\TypeException
     */
    protected function generatePersonalization($emailTo, $nameTo, $message, $i)
    {
        $personalization = new Personalization();

        $email = new To($emailTo, $nameTo);
        $personalization->addTo($email);

        $personalization->setSubject($message->getSubject());

        $mergeTags = $message->getMergeTags();
        foreach ($mergeTags as $varKey => $value) {
            if (is_array($value)) {
                $value = $value[$i];
            }
            $value = (string) $value;
            $personalization->addSubstitution('{{' . $varKey . '}}', $value);
        }

        return $personalization;
    }

    /**
     * @param Message|SwiftSimpleMessage $message
     * @throws SendGrid\Mail\TypeException
     * @throws ReflectionException
     */
    protected function populateContent($message)
    {
        $contentType = $this->getMessagePrimaryContentType($message);

        $bodyHtml = $bodyText = null;

        if ($contentType === 'text/plain') {
            $bodyText = $message->getBody();
        } else {
            $bodyHtml = $message->getBody();
            $bodyText = (new Html2Text($bodyHtml))->getText();
        }

        foreach ($message->getChildren() as $child) {
            if ($child instanceof Swift_Image) {
                $images[] = [
                    'type' => $child->getContentType(),
                    'name' => $child->getId(),
                    'content' => base64_encode($child->getBody()),
                ];
            } elseif ($child instanceof Swift_Attachment && !($child instanceof Swift_Image)) {
                $this->addAttachment($child);
            } elseif ($child instanceof Swift_MimePart && $this->supportsContentType($child->getContentType())) {
                if ($child->getContentType() == "text/html") {
                    $bodyHtml = $child->getBody();
                } elseif ($child->getContentType() == "text/plain") {
                    $bodyText = $child->getBody();
                }
            }
        }

        $content = new Content("text/plain", $bodyText);
        $this->getMail()->addContent($content);

        $content = new Content("text/html", $bodyHtml);
        $this->getMail()->addContent($content);
    }

    /**
     * @param SwiftSimpleMessage $message
     * @return string
     * @throws ReflectionException
     */
    protected function getMessagePrimaryContentType(SwiftSimpleMessage $message)
    {
        $contentType = $message->getContentType();
        if ($this->supportsContentType($contentType)) {
            return $contentType;
        }
        // SwiftMailer hides the content type set in the constructor of Swift_Mime_Message as soon
        // as you add another part to the message. We need to access the protected property
        // _userContentType to get the original type.
        $messageRef = new ReflectionClass($message);
        if ($messageRef->hasProperty('_userContentType')) {
            $propRef = $messageRef->getProperty('_userContentType');
            $propRef->setAccessible(true);
            $contentType = $propRef->getValue($message);
        }

        return $contentType;
    }

    /**
     * @param string $contentType
     * @return bool
     */
    protected function supportsContentType($contentType)
    {
        return in_array($contentType, $this->getSupportedContentTypes());
    }

    /**
     * @return string[]
     */
    protected function getSupportedContentTypes()
    {
        return [
            'text/plain',
            'text/html',
        ];
    }

    /**
     * @param Swift_Attachment $attachment
     * @throws SendGrid\Mail\TypeException
     */
    protected function addAttachment($attachment)
    {
        $sgAttachment = new Attachment();
        $sgAttachment->setContent(base64_encode($attachment->getBody()));
        $sgAttachment->setType($attachment->getContentType());
        $sgAttachment->setFilename($attachment->getFilename());
        $sgAttachment->setDisposition("attachment");
        $sgAttachment->setContentID($attachment->getId());
        $this->getMail()->addAttachment($sgAttachment);
    }

    /**
     * @param Message|SwiftSimpleMessage $message
     * @throws SendGrid\Mail\TypeException
     */
    protected function populateCustomArg($message)
    {
        $args = $message->getCustomArgs();
        foreach ($args as $key => $value) {
            if ($key == 'category') {
                $this->getMail()->addCategory($value);
            } else {
                $this->getMail()->addCustomArg($key, (string) $value);
            }
        }
    }

    /**
     * @return int
     * @throws Swift_TransportException
     */
    protected function sendApiCall()
    {
        $sendGrid = $this->createApi();
        try {
            $response = $sendGrid->send($this->getMail());
        } catch (Exception $exception) {
            throw new Swift_TransportException(
                'Error sending email Code [' . $exception->getMessage() . ']'
            );
        }

        if ($response->statusCode() == '202') {
            return 1;
        } else {
            throw new Swift_TransportException(
                'Error sending email Code [' . $response->statusCode() . ']. '
                . 'HEADERS [' . print_r($response->headers())
                . $response->body()
            );
        }
    }

    /**
     * @return SendGrid
     * @throws Swift_TransportException
     */
    protected function createApi()
    {
        if ($this->getApiKey() === null) {
            throw new Swift_TransportException('Cannot create instance of \SendGrid while API key is NULL');
        }

        return new SendGrid($this->getApiKey());
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
