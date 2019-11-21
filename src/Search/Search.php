<?php


namespace Hanson\Weibot\Search;


use Hanson\Weibot\Api\Api;
use Hanson\Weibot\Page;
use Symfony\Component\DomCrawler\Crawler;

class Search extends Page
{
    /**
     * 上一次搜索的关键词
     * @var string
     */
    private $lastKeyword;

    /**
     * 获取所搜结果的HTML
     *
     * @param $params
     * @return string
     */
    public function getHtml($params = [])
    {
        $response = Api::getClient()->get(sprintf('http://s.weibo.com/weibo/%s&typeall=1&suball=1&timescope=custom:%s:%s&page=%d', $params['keyword'], $params['start_at'] ?? '', $params['end_at'] ?? '', $params['page'] ?? 1));

        return $response->getBody()->getContents();
    }

    /**
     * 获取搜索的 Crawler 对象
     *
     * @param $params
     * @return Crawler
     */
    public function getCrawler($params = [])
    {
        $html = $this->getHtml($params);

        $crawler = new Crawler($html);

        if ($params['keyword'].($params['start_at'] ?? '').($params['end_at'] ?? '') !== $this->lastKeyword) {
            $this->totalPage = $this->getTotalPage($crawler);
        }

        return $crawler;
    }

    /**
     * 获取搜索的整理后数据
     *
     * @param $params
     * @return array
     */
    public function getData($params = [])
    {
        $crawler = $this->getCrawler($params);
        $data = [];
        if ($crawler->filter('.card-no-result')->count() == 0) {
            $crawler->filter('div[action-type="feed_list_item"]')->each(function (Crawler $crawler, $i) use (&$data) {
                if ($crawler->filter('a[action-type="fl_unfold"]')->count() > 1) {
                    $content = $crawler->filter('p[node-type="feed_list_content_full"]');
                } else {
                    $content = $crawler->filter('p[node-type="feed_list_content"]');
                }
                $act = $crawler->filter('.card-act ul li');
                $icons = $content->filter('i.wbicon');
                $location = null;
                if ($icons->count()) {
                    $icons->each(function (Crawler $crawler, $i) use (&$location) {
                        if ($crawler->text() == 2) {
                            $location = str_replace('2', '', $crawler->parents()->text());
                        }
                    });
                }
                $topics = [];
                $a = $content->first()->filter('a');
                if ($a->count()) {
                    $a->each(function (Crawler $crawler, $i) use (&$topics) {
                        $text = $crawler->text();
                        if (mb_substr($text, 0, 1) === '#') {
                            $topics[] = [
                                'name' => $text,
                                'url' => $crawler->attr('href')
                            ];
                        }
                    });
                }
                $source = $crawler->filter('p.from a')->eq(1);
                $data[] = [
                    'mid' => $crawler->attr('mid'),
                    'nickname' => $crawler->filter('.name')->first()->text(),
                    'user_url' => 'https:'.$crawler->filter('.name')->first()->attr('href'),
                    'content' => $content->html(),
                    'time' => $crawler->filter('p.from a')->first()->text(),
                    'url' => 'https:'.$crawler->filter('p.from a')->first()->attr('href'),
                    'source' => $source->count() ? $source->text() : null,
                    'favorite' => trim(str_replace('收藏', '', $act->eq(0)->text())) ?: 0,
                    'forward' => trim(str_replace('转发', '', $act->eq(1)->text())) ?: 0,
                    'comment' => trim(str_replace('评论', '', $act->eq(2)->text())) ?: 0,
                    'like' => trim($act->eq(3)->text()) ?: 0,
                    'location' => $location,
                    'topics' => $topics
                ];
            });
        }
        return [
            'total' => $this->totalPage,
            'data' => $data,
        ];
    }

    /**
     * 获取总页数
     *
     * @param Crawler $crawler
     * @return int
     */
    protected function getTotalPage(Crawler $crawler)
    {
        if ($crawler->filter('.card-no-result')->count()) {
            return 0;
        }

        return $crawler->filter('.s-scroll li')->count();
    }
}