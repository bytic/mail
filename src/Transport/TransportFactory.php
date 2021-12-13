<?php

namespace Nip\Mail\Transport;

use InvalidArgumentException;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport as SmtpTransport;

/**
 * Class TransportManager.
 */
class TransportFactory
{
    /**
     * @param string $name
     *
     * @return AbstractTransport
     */
    public function fromConfig($config)
    {
        $name = $config['transport'];

        if ('' === trim($name) || !method_exists($this, $method = 'create'.ucfirst($name).'Transport')) {
            throw new InvalidArgumentException("Unsupported mail transport [{$name}].");
        }

        return $this->{$method}($config);
    }

    /**
     * Create an instance of the Mailgun Swift Transport driver.
     */
    protected function createSendgridTransport(array $config): SendgridApiTransport
    {
        $transport = new SendgridApiTransport($config['api_key']);

        return $transport;
    }

    /**
     * Create an instance of the SMTP Swift Transport driver.
     *
     * @return SmtpTransport
     */
    protected function createSmtpTransport(array $config)
    {// The Swift SMTP transport instance will allow us to use any SMTP backend
        // for delivering mail such as Sendgrid, Amazon SES, or a custom server
        // a developer has available. We will just pass this configured host.
        $transport = new SmtpTransport(
            $config['host'],
            $config['port']
        );

//        if (!empty($config['encryption'])) {
//            $transport->setEncryption($config['encryption']);
//        }

        // Once we have the transport we will check for the presence of a username
        // and password. If we have it we will set the credentials on the Swift
        // transporter instance so that we'll properly authenticate delivery.
        if (isset($config['username'])) {
            $transport->setUsername($config['username']);

            $transport->setPassword($config['password']);
        }

        return $this->configureSmtpTransport($transport, $config);
    }

    /**
     * Configure the additional SMTP driver options.
     *
     * @param SmtpTransport $transport
     *
     * @return SmtpTransport
     */
    protected function configureSmtpTransport($transport, array $config)
    {
        if (isset($config['stream'])) {
            $transport->setStreamOptions($config['stream']);
        }

        if (isset($config['source_ip'])) {
            $transport->setSourceIp($config['source_ip']);
        }

        if (isset($config['local_domain'])) {
            $transport->setLocalDomain($config['local_domain']);
        }

        if (isset($config['timeout'])) {
            $transport->setTimeout($config['timeout']);
        }

        if (isset($config['auth_mode'])) {
            $transport->setAuthMode($config['auth_mode']);
        }

        return $transport;
    }
}
