<?php


namespace Hanson\Weibot\Follow;


use Hanson\Weibot\Api\Api;
use Hanson\Weibot\Page;
use Hanson\Weibot\Weibot;
use Symfony\Component\DomCrawler\Crawler;

class Follow extends Page
{
    /**
     * @var Weibot
     */
    private $weibo;

    public function __construct(Weibot $weibo)
    {
        $this->weibo = $weibo;
    }

    public function unfollow($uid)
    {
        $response = Api::getClient()->post('https://weibo.com/aj/f/unfollow?ajwvr=6&__rnd='.time().'000', [
            'form_params' => [
                'uid' => $uid,
                'refer_flag' => 'unfollow_all'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getHtml($params = [])
    {
        $response = Api::getClient()->get(sprintf('https://weibo.com/p/100505%s/myfollow', Weibot::$uid));

        return $response->getBody()->getContents();
    }

    public function getCrawler($params = [])
    {
        $html = $this->getHtml($params);

        preg_match_all('/pl.relation.myFollow.index.*html\":\"(.*)\"}/', $html, $matches);

        $html = str_replace('\/', '/', str_replace('\"', '"', $matches[1][0]));

        $crawler = new Crawler($html);

        $this->totalPage = $this->getTotalPage($crawler);

        return $crawler;
    }

    public function getData($params = [])
    {
        $crawler = $this->getCrawler($params);

        $data = [];

        if ($crawler->filter('.W_pages a.page')->count()) {
            $crawler->filter('.member_box ul.member_ul li.member_li')->each(function (Crawler $crawler) use (&$data) {
                $img = $crawler->filter('.pic_box a')->html();

                preg_match('/src="(.*?)".*title="(.*?)"/', $img, $matches);

                $data[] = [
                    'avatar' => $matches[1],
                    'nickname' => $matches[2],
                    'uid' => str_replace('id=', '', $crawler->filter('.W_autocut a')->first()->attr('usercard')),
                    'desc' => trim(str_replace('\r\n', '', $crawler->filter('.W_autocut.S_txt2')->text())),
                ];
            });
        }

        return [
            'total' => $this->totalPage,
            'data' => $data,
        ];
    }

    protected function getTotalPage(Crawler $crawler)
    {
        $page = $crawler->filter('.W_pages a.page');

        if (!$page->count()) {
            return 0;
        }

        $count = $page->last()->text();

        if (is_int($count)) {
            return $count;
        } else {
            return $page->eq($page->count() - 2)->text();
        }
    }
}