<?php
declare(strict_types=1);

namespace Nip\Mail\Utility;

use Symfony\Component\Mime\Address as SymfonyAddress;

/**
 *
 */
class Address
{
    public static function fromArray(array $addresses): array
    {
        $return = [];
        foreach ($addresses as $key => $value) {
            if (is_int($key)) {
                $return[$key] = static::fromString($value);
                continue;
            }
            if (is_string($key) && is_string($value)) {
                $return[$key] = static::create($key, $value);
                continue;
            }
        }
        return $return;
    }

    public static function fromString(string $string): SymfonyAddress
    {
        return SymfonyAddress::create($string);
    }

    /**
     * @param $email
     * @param $name
     * @return SymfonyAddress
     */
    public static function create($email, $name = null): SymfonyAddress
    {
        return new SymfonyAddress($email, $name);
    }
}