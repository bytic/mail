<?php

namespace Nip\Mail\MessageBuilder;

use Nip\Mail\Utility\Mail;

trait CanSend
{
    public function send($mailer = null)
    {
        return Mail::mailer($mailer)->send($this->getMessage());
    }
}
