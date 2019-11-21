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

## 参与开发更多 API

基于 weibot，开发微博的抓包工作会更加简单

### 一、登录

微博很多操作都需要登录，所以写脚本的时候先登录，让 cookie 存储起来 `$weibo->login()`

### 二、抓包

根据浏览器看到的请求，我们可以尝试模拟一下

```php
<?php
// $client 已经是一个带 cookie 的“浏览器”客户端了，根据实际情况进行 get 或者 post
$client = \Hanson\Weibot\Api\Api::getClient();

$response = $client->post('http://weibo.com', [
    'header' => [
        // 如果有特殊 header 需求    
    ],
    'form_params' => [
        // 各种请求参数
    ]
]);

// 得到的 response 有可能是页面，也有可能是接口，自行处理
$data = json_decode($response->getBody()->getContents(), true);
```

### 相关头绪

* 微博部分页面是基于页面渲染的模式
* 微博的渲染并不按套路出牌，而是使用了 FM.view 的内部框架
* 抓取内容需要先在 script 里正则匹配出来相关的 HTML。 例如：`preg_match_all('/Pl_Official_MyProfileFeed__20.*html\":\"(.*)\"}/', $html, $matches);`
* 有部分地方是异步接口的，例如下滑滚动分页