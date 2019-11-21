<?php


namespace Hanson\Weibo\Auth;


use Hanson\Weibo\Weibo;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AuthServiceProvider implements ServiceProviderInterface
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
        $pimple['auth'] = function (Weibo $pimple) {
            $config = $pimple->getConfig();
            return new Auth($config['username'], $config['password']);
        };
    }
}