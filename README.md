# weibot

微博机器人

## 安装

`composer require hanson/weibot:dev-master -vvv`

## 文档

### 登录

```php
<?php

include_once __DIR__.'/../vendor/autoload.php';

$weibo = new \Hanson\Weibot\Weibot([
    'username' => '',
    'password' => '',
    'cookie_path' => __DIR__.'/cookie', // cookie 存储路径
    'debug' => []
]);
```

### 搜索 Search

```php
<?php
$search = $weibo->search;

$search->getData([
    'keyword' => '关键词',
//     'page' => 1, // 页数
//    'start_at' => '2019-11-07-6', # yyyy-mm-dd-h 时间筛选
//    'end_at' => '2019-12-07-8',
]);
```

### 我的关注 Follow

```php
<?php
$follow = $weibo->follow;

$follow->getData([
//     'page' => 1, // 页数
]);
```

### 我的关注 Follow

```php
<?php
$follow = $weibo->follow;

$follow->getData([
//     'page' => 1, // 页数
]);
```

### 微博 Post

```php
<?php
$post = $weibo->post;

/**
 * 评论微博
 *
 * @param $mid string 微博 id
 * @param $content string 评论内容
 * @param bool $forward 是否转发
 * @return mixed
 */
$post->comment($mid, $content, $forward = false)

/**
 * 转发微博
 *
 * @param $mid
 * @param $reason string 转发内容
 * @param bool $isComment 是否评论
 * @param bool $isCommentBase 是否对原微博评论
 * @return mixed
 */
$post->forward($mid, $reason, $isComment = false, $isCommentBase = false)

/**
 * 删除微博
 * 
 * @param $mid string 微博id
 * @return false|string
 */
$post->delete($mid)

// 我的微博（待开发）
$post->getData()
```