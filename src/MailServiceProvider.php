<?php

namespace Nip\Mail;

use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;
use Nip\Mail\Transport\TransportFactory;
use Nip\Router\Router;

/**
 * Class MailServiceProvider.
 */
class MailServiceProvider extends AbstractSignatureServiceProvider
{
    public const NAME = 'mail';

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerTransportManager();
        $this->registerMailer();
    }

    protected function registerTransportManager()
    {
        $factory = new TransportFactory();
        $this->getContainer()->share('mailer.transportManager', function () use ($factory) {
            return $factory;
        });
        $this->getContainer()->share(TransportFactory::class, function () {
            return $this->getContainer()->get('mailer.transportManager');
        });
    }

    protected function registerMailer()
    {
        $this->getContainer()->share('mailer', function () {
            $transportManager = $this->getContainer()->get(TransportFactory::class);
            $mailer = new MailerManager($transportManager);

            return $mailer;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'mailer',
            TransportFactory::class,
            'mailer.transportManager',
        ];
    }
}
