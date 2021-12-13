<?php

namespace Nip\Mail;

use InvalidArgumentException;
use Nip\Config\Utils\PackageHasConfigTrait;
use Nip\Container\Utility\Container;
use Nip\Mail\Transport\TransportFactory;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

/**
 *
 */
class MailerManager implements MailerInterface
{
    use PackageHasConfigTrait;

    protected $transportManager = null;

    /**
     * The array of resolved mailers.
     *
     * @var array
     */
    protected $mailers = [];

    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->mailer()->send($message, $envelope);
    }

    /**
     * Get a mailer instance by name.
     *
     * @param string|null $name
     * @return Mailer
     */
    public function mailer($name = null): Mailer
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->mailers[$name] = $this->get($name);
    }

    /**
     * Attempt to get the mailer from the local cache.
     *
     * @param string $name
     * @return Mailer
     */
    protected function get($name)
    {
        return $this->mailers[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given mailer.
     *
     * @param string $name
     * @return Mailer
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = static::getPackageConfig('mailers.' . $name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Mailer [{$name}] is not defined.");
        }

        $config = $config->toArray();
        // Once we have created the mailer instance we will set a container instance
        // on the mailer. This allows us to resolve mailer classes via containers
        // for maximum testability on said classes instead of passing Closures.
        $mailer = new Mailer(
            $this->transportManager()->fromConfig($config),
        );

        return $mailer;
    }
    /**
     * Get the default mail driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): ?string
    {
        // Here we will check if the "driver" key exists and if it does we will use
        // that as the default driver in order to provide support for old styles
        // of the Laravel mail configuration file for backwards compatibility.
        return static::getPackageConfig('default');
    }

    /**
     * @return mixed
     */
    public function transportManager()
    {
        if ($this->transportManager === null) {
            $this->transportManager = Container::container()->get(TransportFactory::class);
        }
        return $this->transportManager;
    }

    /**
     * @param mixed $transportManager
     */
    public function setTransportManager($transportManager): void
    {
        $this->transportManager = $transportManager;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->mailer()->$method(...$parameters);
    }

    /**
     * {@inheritDoc}
     */
    protected static function getPackageConfigName(): string
    {
        return MailServiceProvider::NAME;
    }
}