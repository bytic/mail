<?php

namespace Nip\Mail\MessageBuilder;

use Symfony\Component\Mime\Address;

/**
 *
 */
trait MessageProxy
{
    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, $parameters)
    {
        return $this->message->$method(...$parameters);
    }

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->message->subject($subject);

        return $this;
    }

    public function addTo(...$addresses)
    {
        $this->addAddresses($addresses, 'to');
    }

    /**
     * @param $addresses
     * @param $type
     * @return $this
     */
    protected function addAddresses($addresses, $type): self
    {
        $method = 'add' . ucfirst($type);
        $addresses = $this->addressesToArray($addresses);

        $this->message->$type(...$addresses);
        return $this;
    }

    /**
     * @param $addresses
     * @return Address[]
     */
    protected function addressesToArray($addresses): array
    {
        if (count($addresses) === 1) {
            return [new Address($addresses[0])];
        }

        if (count($addresses) === 2) {
            return [new Address($addresses[0], $addresses[1])];
        }

        $result = [];
        foreach ($addresses as $address) {
                $result[] = Address::create($address);
        }
        return $result;
    }
}