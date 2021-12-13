<?php

namespace Nip\Mail\MessageBuilder;

trait CanBuild
{
    protected $isBuild = false;
    protected $buildMethods = ['buildFrom', 'buildRecipients', 'buildSubject', 'buildBody', 'buildAttachments'];

    protected function guardIsBuild()
    {
        if ($this->isBuild) {
            return;
        }
        $this->build();
        $this->isBuild = true;
    }

    protected function build()
    {
        foreach ($this->buildMethods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
        $this->runCallbacks();
    }
}
