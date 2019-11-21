<?php

use Hanson\Weibot\Api\Api;

include_once __DIR__.'/../vendor/autoload.php';

$weibo = new \Hanson\Weibot\Weibot([
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