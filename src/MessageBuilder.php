<?php

namespace Nip\Mail;

class MessageBuilder
{
    use MessageBuilder\CanBuild;
    use MessageBuilder\CanSend;
    use MessageBuilder\HasCallbacks;
    use MessageBuilder\MessageProxy;
    protected $message;

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
