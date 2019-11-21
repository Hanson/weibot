<?php


namespace Hanson\Weibo;

use Hanson\Foundation\Foundation;
use Hanson\Weibo\Api\ApiServiceProvider;
use Hanson\Weibo\Auth\AuthServiceProvider;
use Hanson\Weibo\Follow\FollowServiceProvider;
use Hanson\Weibo\Post\PostServiceProvider;
use Hanson\Weibo\Search\SearchServiceProvider;
use Hanson\Weibo\User\UserServiceProvider;

/**
 * Class Weibo
 * @package Hanson\Weibo
 * @property \Hanson\Weibo\Auth\Auth   $auth
 * @property \Hanson\Weibo\User\User   $user
 * @property \Hanson\Weibo\Search\Search $search
 * @property \Hanson\Weibo\Follow\Follow $follow
 * @property \Hanson\Weibo\Post\Post $post
 */
class Weibo extends Foundation
{
    public static $uid;

    protected $providers = [
        ApiServiceProvider::class,
        AuthServiceProvider::class,
        SearchServiceProvider::class,
        UserServiceProvider::class,
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