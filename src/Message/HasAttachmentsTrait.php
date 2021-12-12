<?php

namespace Nip\Mail\Message;

/**
 * Trait HasAttachmentsTrait.
 */
trait HasAttachmentsTrait
{
    /**
     * @param $content
     * @param $name
     *
     * @deprecated use attach() instead
     */
    public function attachFromContent($content, $name = null, $contentType = null)
    {
        return $this->attach($content, $name, $contentType);
    }
}
