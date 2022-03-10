<?php
declare(strict_types=1);

namespace Nip\Mail;

/**
 * Class Message.
 */
class Message extends \Symfony\Component\Mime\Email
{
    use Message\HasMergeArgsTrait;
    use Message\HasAttachmentsTrait;

    /**
     * @param $from
     * @return $this
     */
    public function setFrom($from): Message
    {
        return $this->addFrom($from);
    }
}
