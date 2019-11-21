<?php


namespace Hanson\Weibot\Search;


use Hanson\Weibot\Weibot;
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
        $pimple['search'] = function (Weibot $pimple) {
            return new Search();
        };
    }
}