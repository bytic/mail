<?php

namespace Nip\Mail;

/**
 * Class Message
 * @package Nip\Mail
 */
class Message extends \Symfony\Component\Mime\Email
{
    use Message\HasMergeArgsTrait;
    use Message\HasAttachmentsTrait;
}
