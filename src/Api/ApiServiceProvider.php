<?php


namespace Hanson\Weibot\Api;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ApiServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        Api::setClient($pimple->getConfig('cookie_path'));
    }
}