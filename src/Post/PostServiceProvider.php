<?php


namespace Hanson\Weibo\Post;


use Hanson\Weibo\Weibo;
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
        $pimple['post'] = function (Weibo $pimple) {
            return new Post();
        };
    }
}