<?php
declare(strict_types=1);

namespace Nip\Mail\Utility;

use Nip\Container\Utility\Container;
use Nip\Mail\Transport\TransportFactory;

/**
 *
 */
class Mail
{
    /**
     * @param $mailer
     * @return mixed|null
     */
    public static function mailer($mailer = null)
    {
        return $mailer;
    }

    /**
     * @param $transport
     *
     * @return mixed
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function transport($transport = null)
    {
        return Container::container()->get(TransportFactory::class)->get($transport);
    }
}
