<?php


namespace Hanson\Weibo\Search;


use Hanson\Weibo\Weibo;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SearchServiceProvider implements ServiceProviderInterface
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
        $pimple['search'] = function (Weibo $pimple) {
            return new Search();
        };
    }
}