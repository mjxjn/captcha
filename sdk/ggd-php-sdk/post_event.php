<?php 
require_once dirname(__FILE__) . '/class.geeguard.php';
require_once dirname(__FILE__) . '/config.php';

$GeeGuard = new GeeguradLib(gid, key);

$event_attr = array(
        		'terminal'=>'pc',  # 发生此事件用户的终端
        		'level'=>'3'  # 当前事件的重要性（0(不重要)-> 5(非常重要)）
        		# ... other attribution
			);
$geeguardData = $_POST['geeguardData'];
$data = array(
        'event_type'=>"login",  // required,事件的类型,预设 login logout common register 
        'event_id'=>"php_sdk_demo_event", // required,事件id
        'event_attr'=>$event_attr, // option
        'uid'=>'php_sdk_demo', //用户id
        'geeguardData'=>$geeguardData
    );

$result = $GeeGuard->eventhandler($data);
echo $result;

 ?>