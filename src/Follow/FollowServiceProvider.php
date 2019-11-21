<?php


namespace Hanson\Weibot\Follow;


use Hanson\Weibot\Weibot;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FollowServiceProvider implements ServiceProviderInterface
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
        $pimple['follow'] = function (Weibot $pimple) {
            return new Follow($pimple);
        };
    }
}