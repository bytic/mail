<?php
declare(strict_types=1);

namespace Nip\Mail;

/**
 *
 */
trait LegacyMessageMethods
{

    /**
     * @param $from
     * @deprecated use addFrom() instead
     * @return $this
     */
    public function setFrom($from): self
    {
        return $this->addFrom($from);
    }

    /**
     * @param $subject
     * @deprecated use subject() instead
     * @return $this
     */
    public function setSubject($subject): self
    {
        return $this->subject($subject);
    }
}
