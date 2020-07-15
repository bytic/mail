<?php

namespace Nip\Mail;

use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;

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
        $this->getContainer()->share('mailer.transport', function () {
            $transportManager = new TransportManager();
            return $transportManager->transport();
        });
    }

    protected function registerMailer()
    {
        $this->getContainer()->share('mailer', function () {
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
