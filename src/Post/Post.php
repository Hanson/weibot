<?php


namespace Hanson\Weibot\Post;


use Hanson\Weibot\Api\Api;
use Hanson\Weibot\Page;
use Hanson\Weibot\Weibot;
use Symfony\Component\DomCrawler\Crawler;

class Post extends Page
{
    /**
     * 评论微博
     *
     * @param $mid string 微博 id
     * @param $content string 评论内容
     * @param bool $forward 是否转发
     * @return mixed
     */
    public function comment($mid, $content, $forward = false)
    {
        $response = Api::getClient()->post('https://weibo.com/aj/v6/comment/add?ajwvr=6&__rnd=1573440747324', [
            'form_params' => [
                'mid' => $mid,
                'forward' => $forward,
                'content' => $content,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 转发微博
     *
     * @param $mid
     * @param $reason string 转发内容
     * @param bool $isComment 是否评论
     * @param bool $isCommentBase 是否对原微博评论
     * @return mixed
     */
    public function forward($mid, $reason, $isComment = false, $isCommentBase = false)
    {
        $response = Api::getClient()->post('https://weibo.com/aj/v6/mblog/forward', [
            'form_params' => [
                'reason' => $reason,
                'mid' => $mid,
                'is_comment' => $isComment,
                'is_comment_base' => $isCommentBase,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 删除微博
     *
     * @param $mid string 微博id
     * @return false|string
     */
    public function delete($mid)
    {
        $response = Api::getClient()->post('https://weibo.com/aj/mblog/del', [
            'form_params' => [
                'mid' => $mid,
            ]
        ]);

        return json_encode($response->getBody()->getContents());
    }

    /**
     * 发表微博
     *
     * @param $text string 发表的内容
     * @return mixed
     */
    public function send($text)
    {
        $response = Api::getClient()->post('https://weibo.com/aj/mblog/add?ajwvr=6&__rnd=1583565934348', [
            'form_params' => [
                'text' => $text,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getHtml($params = [])
    {
        $response = Api::getClient()->get(sprintf('https://weibo.com/p/100505%s/home?profile_ftype=1&is_all=1', Weibot::$uid));

        return $response->getBody()->getContents();
    }

    public function getCrawler($params = [])
    {
        $html = $this->getHtml($params);

        preg_match_all('/Pl_Official_MyProfileFeed__20.*html\":\"(.*)\"}/', $html, $matches);

        $html = str_replace('\/', '/', str_replace('\"', '"', $matches[1][0]));
        echo $html;exit;
        $crawler = new Crawler($html);

        $this->totalPage = $this->getTotalPage($crawler);

        return $crawler;
    }

    public function getData($params = [])
    {
        // TODO: Implement getData() method.
    }

    protected function getTotalPage(Crawler $crawler)
    {
        // TODO: Implement getTotalPage() method.
    }
}