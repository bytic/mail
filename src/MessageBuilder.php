<?php

namespace Nip\Mail;

/**
 *
 */
class MessageBuilder
{
    protected $message;

    use MessageBuilder\CanBuild;
    use MessageBuilder\CanSend;
    use MessageBuilder\HasCallbacks;
    use MessageBuilder\MessageProxy;

    public function __construct()
    {
        $this->message = new Message();
    }

    public function getMessage(): Message
    {
        $this->guardIsBuild();
        return $this->message;
    }
}