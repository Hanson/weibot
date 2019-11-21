<?php


namespace Hanson\Weibo\User;


use Hanson\Weibo\Api\Api;
use Symfony\Component\DomCrawler\Crawler;

class User
{
    public function posts($uid)
    {

    }

    public function getPostsFormUrl($url)
    {
        $response = Api::getClient()->get($url);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        print_r($crawler);exit;
        echo $crawler->filter('div[action-type="feed_list_item"]')->count();exit;
        $crawler->filter('div[action-type="feed_list_item"]')->each(function (Crawler $crawler) {
            echo $crawler->attr('mid');
        });
    }
}