<?php

namespace Nip\Mail;

/**
 * Class Message.
 */
class Message extends \Symfony\Component\Mime\Email
{
    use Message\HasMergeArgsTrait;
    use Message\HasAttachmentsTrait;
}
