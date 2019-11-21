<?php


namespace Hanson\Weibot;

use Hanson\Foundation\Foundation;
use Hanson\Weibot\Api\ApiServiceProvider;
use Hanson\Weibot\Auth\AuthServiceProvider;
use Hanson\Weibot\Follow\FollowServiceProvider;
use Hanson\Weibot\Post\PostServiceProvider;
use Hanson\Weibot\Search\SearchServiceProvider;

/**
 * Class Weibot
 * @package Hanson\Weibot
 * @property \Hanson\Weibot\Auth\Auth   $auth
 * @property \Hanson\Weibot\Search\Search $search
 * @property \Hanson\Weibot\Follow\Follow $follow
 * @property \Hanson\Weibot\Post\Post $post
 */
class Weibot extends Foundation
{
    public static $uid;

    protected $providers = [
        ApiServiceProvider::class,
        AuthServiceProvider::class,
        SearchServiceProvider::class,
        FollowServiceProvider::class,
        PostServiceProvider::class,
    ];

    public function login()
    {
        $result = $this->auth->login();

        static::$uid = $result['userinfo']['uniqueid'];

        return $this;
    }
}