<?php

namespace Nip\Mail\MessageBuilder;

use Nip\Mail\Message;

/**
 *
 */
trait HasCallbacks
{
    /**
     * The callbacks for the message.
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * The callback that should be invoked while building the view data.
     *
     * @var callable
     */
    public static $viewDataCallback;

    /**
     * Run the callbacks for the message.
     *
     * @param Message $message
     * @return $this
     */
    protected function runCallbacks(): self
    {
        foreach ($this->callbacks as $callback) {
            $callback($this->message);
        }

        return $this;
    }
}