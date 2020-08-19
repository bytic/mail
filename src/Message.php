<?php

namespace Nip\Mail;

use Swift_Message;

/**
 * Class Message
 * @package Nip\Mail
 */
class Message extends Swift_Message
{
    use Message\HasMergeArgsTrait;
    use Message\HasAttachmentsTrait;
}
