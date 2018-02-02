<?php 
require_once dirname(__FILE__) . '/class.geeguard.php';
require_once dirname(__FILE__) . '/config.php';

# 对uid用private_key签名
$uid = "123456@qq.com";
$GeeGuard = new GeeguradLib(CAPTCHA_ID, PRIVATE_KEY);
$sign = $GeeGuard->sign_uid($uid);
$data = array("uid"=>$uid, "sign"=>$sign);
echo json_encode($data);  #返回给前端

?>