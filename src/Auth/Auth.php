<?php


namespace Hanson\Weibot\Auth;


use Hanson\Weibot\Api\Api;
use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;

class Auth
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = base64_encode($username);
        $this->password = $password;
    }

    /**
     * 登录
     */
    public function login()
    {
        $data = $this->preLogin();

        $loginUrl = $this->sinaLogin($data);

        return $this->weiboLogin($loginUrl);
    }

    /**
     * 预登录
     *
     * @return mixed
     * @throws \Hanson\Foundation\Exception\HttpException
     */
    private function preLogin()
    {
        $response = Api::getClient()->get("http://login.sina.com.cn/sso/prelogin.php?entry=weibo&callback=sinaSSOController.preloginCallBack&su={$this->username}&rsakt=mod&checkpin=1&client=ssologin.js(v1.4.18)&_=");

        $result = $response->getBody()->getContents();

        $json = str_replace(['sinaSSOController.preloginCallBack', '(', ')'], '', $result);

        return json_decode($json, true);
    }

    private function sinaLogin($preLoginData)
    {
        $response = Api::getClient()->post('http://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.11)', [
            'form_params' => [
                'su' => $this->username,
                'servertime' => $preLoginData['servertime'],
                'nonce' => $preLoginData['nonce'],
                'sp' => $this->getPassword($preLoginData),
                'rsakv' => $preLoginData['rsakv'],
                'entry' => 'sso',
                'gateway' => '1',
                'from' => 'null',
                'savestate' => '0',
                'useticket' => '0',
                'pagerefer' => "http://login.sina.com.cn/sso/logout.php?entry=miniblog&r=http%3A%2F%2Fweibo.com%2Flogout.php%3Fbackurl",
                'vsnf' => '1',
                'service' => 'sso',
                'pwencode' => 'rsa2',
                'sr' => '1366*768',
                'encoding' => 'UTF-8',
                'cdult' => '3',
                'domain' => 'sina.com.cn',
                'prelt' => '27',
                'returntype' => 'TEXT'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if ($result['retcode'] != 0) {
            throw new \Exception($result['reason']);
        }

        return $result['crossDomainUrlList'][0];
    }

    private function weiboLogin($url)
    {
        $response = Api::getClient()->get($url);

        $json = str_replace(['(', ')', ';'], '', $response->getBody()->getContents());

        return json_decode($json, true);
    }

    private function getPassword($data) {
        $rsa = new RSA();

        $rsa->loadKey([
            'n' => new BigInteger($data['pubkey'], 16),
            'e' => new BigInteger('10001', 16),
        ]);

        $message = $data['servertime']."\t".$data['nonce']."\n".$this->password;

        $rsa->setEncryptionMode(2);

        return bin2hex($rsa->encrypt($message));
    }
}