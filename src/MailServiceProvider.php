<?php

namespace Nip\Mail;

use Nip\Container\ServiceProvider\AbstractSignatureServiceProvider;

/**
 * Class MailServiceProvider
 * @package Nip\Mail
 */
class MailServiceProvider extends AbstractSignatureServiceProvider
{

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerTransport();
        $this->registerMailer();
    }

    protected function registerTransport()
    {
        $this->getContainer()->singleton('mailer.transport', function () {
            $transportManager = new TransportManager();
            return $transportManager->create();
        });
    }

    protected function registerMailer()
    {
        $this->getContainer()->singleton('mailer', function () {
            $transport = $this->getContainer()->get('mailer.transport');
            $mailer = new Mailer($transport);
            return $mailer;
        });
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['mailer', 'mailer.transport'];
    }
}
