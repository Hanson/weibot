<?php

use Hanson\Weibo\Api\Api;

include_once __DIR__.'/../vendor/autoload.php';

$weibo = new \Hanson\Weibo\Weibo([
    'username' => '',
    'password' => '',
    'cookie_path' => __DIR__.'/cookie',
    'debug' => []
]);

$follow = $weibo->login()->follow;

while (true) {
    $result = $follow->getData();

    if (!$result['data']) {
        return;
    }

    $uids = [];

    foreach ($result['data'] as $item) {
        $uids[] = $item['uid'];
    }

    $result = $follow->unfollow(implode(',', $uids));
}