<?php


namespace Hanson\Weibot\Post;


use Hanson\Weibot\Weibot;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PostServiceProvider implements ServiceProviderInterface
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
        $pimple['post'] = function (Weibot $pimple) {
            return new Post();
        };
    }
}