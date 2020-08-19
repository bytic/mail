<?php

namespace Nip\Mail\Message;

use Swift_Attachment;

/**
 * Trait HasAttachmentsTrait
 * @package Nip\Mail\Message
 */
trait HasAttachmentsTrait
{
    /**
     * @param $content
     * @param $name
     */
    public function attachFromContent($content, $name = null, $contentType = null)
    {
        $attachment = new Swift_Attachment($content, $name);
        $this->attach($attachment);

    }
}
