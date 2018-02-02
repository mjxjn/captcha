<?php

/**
 * 极验geeguard库文件
 *
 * @author Tanxu
 */
class GeeguradLib {

    public static $connectTimeout = 1;
    public static $socketTimeout  = 1;

    public function __construct($captcha_id, $private_key) {
        $this->captcha_id  = $captcha_id;
        $this->private_key = $private_key;
    }

    public function sign_uid($uid){
        $sign = md5($uid.$this->private_key);
        return $sign;
    }

    public function eventhandler($data){
        $url = 'http://apiguard.geetest.com/event.php';
        $data['gt_id'] = $this->captcha_id;
        $data['private_key'] = $this->private_key;
        $data['time'] = time();
        $param = json_encode($data);
        $result = $this->post_request($url, $param);
        return $result;

    }

    /**
     *
     * @param       $url
     * @param array $postdata
     * @return mixed|string
     */
    private function post_request($url, $postdata = '') {
        if (!$postdata) {
            return false;
        }

        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);

            //不可能执行到的代码
            if (!$postdata) {
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            }
            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                $err = sprintf("curl[%s] error[%s]", $url, curl_errno($ch) . ':' . curl_error($ch));
                $this->triggerError($err);
            }

            curl_close($ch);
        } else {
            if ($postdata) {
                $opts    = array(
                    'http' => array(
                        'method'  => 'POST',
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($postdata) . "\r\n",
                        'content' => $postdata,
                        'timeout' => self::$connectTimeout
                    )
                );
                $context = stream_context_create($opts);
                $data    = file_get_contents($url, false, $context);
            }
        }

        return $data;
    }

    /**
     * @param $err
     */
    private function triggerError($err) {
        trigger_error($err);
    }



}
?>