<?php
namespace saga\captcha;

trait Traits{

    /**
     * 第一次验证
     * @param $user_id
     * @return mixed
     */
    protected function getGeetestCode($user_id){
        $client = $this->getGeetestClient();
        return $client->getCode($user_id);
    }

    /**
     * 第二次验证，返回success后请执行登录等操作
     * @param $user_id
     * @return string
     */
    protected function geetestCheck($user_id){
        $client = $this->getGeetestClient();
        $post = array();
        if(function_exists('request')){
            $post['geetest_challenge'] = request()->post('geetest_challenge');
            $post['geetest_validate'] = request()->post('geetest_validate');
            $post['geetest_seccode'] = request()->post('geetest_seccode');
        } else {
            $post = $_POST;
        }
        $rs = $client->check($user_id, $post);
        if($rs == 1){
            return 'success';
        }
        return 'error';
    }

    protected function getGeetestClient(){
        $config = $this->getZaCaptchaConfig();
        return new Geetest($config);
    }

    protected function getZaCaptchaConfig(){
        return config('zacaptcha.geetest');
    }

    /**
     * @param $id
     * @param $codeUrl
     * @param $checkUrl
     * @param $callBack 成功后js回调函数名
     * @param string $product [float，embed，popup]
     * @return string
     */
    protected function getGeetestHtml($id, $codeUrl){
        $codeUrl .= strpos($codeUrl, '?') === false ? '?' : '&';
        return <<<EOF
<div id="{$id}"></div>
<script src="https://cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
<script src="http://apiguard.geetest.com/getfullpage.php?gt_id=b46d1900d0a894591916ea94ea91bd2c"></script>
<script>
    //关键事件发生时通知后端，并发送事件信息，此为您的业务逻辑
    document.querySelector('#{$id}').onclick = function () {

        //Geeguard.getData()取到本次事件的行为数据，在发生关键事件时，发送给网站主的业务接口，再由业务接口转发给geeguard后台
        var geeguardData = Geeguard.getData()
        $.post('{$codeUrl}', {
            //selfData: 'selfData',  //网站主自身业务数据
            geeguardData: geeguardData   //geeguard数据，发送给业务接口，由业务接口转发给geeguard后台
        });
    }
</script>
EOF;

    }

}