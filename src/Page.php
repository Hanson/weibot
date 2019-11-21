<?php


namespace Hanson\Weibot;


use Symfony\Component\DomCrawler\Crawler;

abstract class Page
{
    /**
     * 此次搜索的总页数
     *
     * @var
     */
    protected $totalPage;

    abstract public function getHtml($params = []);

    abstract public function getCrawler($params = []);

    abstract public function getData($params = []);

    abstract protected function getTotalPage(Crawler $crawler);
}