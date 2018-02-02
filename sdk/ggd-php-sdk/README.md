[TOC]
# Geeguard SDK1.0说明

## GeeguardLib概述

GeeguardLib是极验验证guard数据安全分析项目的sdk主要对象, 提供用户id签名和自定义事件日志传输接口。

## 使用说明

### Web前端
demo地址：https://github/geeguard/web-front....
1. 引入文件
    ```html
        <script type="text/javascript" src="geeguard.geetest.com/getfullpage.php?gt_id=xxxxxxxxxxxxx"></script>
    ```
    在所有需要统计和分析的html页面的body标签底部里面引入以上代码段即可,自动生成`Geeguard`全局对象，id是当您注册我们的服务时获得的。


### Web 前端接口
getfullpage.php请求成功后前端将添加全局对象`Geeguard`。
该对象有以下接口：
* Geeguard.bind(url)
* 在用户登录网站后，前端调用此接口配合后端SDK，将此用户与Geeguard的匿名ID进行绑定。
* 说明：您需要在网站后端设置这个url路由， 在这个url对应的处理函数里调用SDK中GeeguardLib对象中的sign_uid方法对用户id进行签名，并返回签名串和用户id组成的json字符串(详细请查看下面的接口对象GeeguardLib)。
	
### 接口对象GeeguardLib

*  接口对象：
	geeguard  =  GeeguardLib(captcha_id, private_key)
    
     参数| 说明
    -------------|---------------
    参数 captcha_id : string | 网站主注册时获得的的唯一uid
    参数 private_key : string | 网站主注册时获得的private_key
    返回值 | GeeguardLib对象
    描述 | 调用此接口生成一个GeeguardLib对象，用于后续的uid签名。

*   sign_uid :   GeeguardLib对象方法
     参数 | 说明
    -------------|---------------
    参数 uid : string | 传入真实的用户id
    返回值 | 字典：{"sign": sign, "uid": uid}
    描述 | 传入uid进行签名，返回uid和签名串组成的字典。

*   eventhandler :   GeeguardLib对象方法
    参数 | 说明
    -------------|---------------
    data | 数组

    键 | 值
    -------------|---------------
     uid : string | 传入真实的用户id       (required)
     event_type : string | 事件类型：login/logout/common       (required)
     event_id : string | 事件ID         (required)
     event_attr : string | 该事件的其他属性    (option)
    返回值 | 字典：{"status": True/False}
    描述 | 事件类型, 事件id, 您在极验申请的id和private_key, 触发此事件的用户id。如果有需求可以传入更加详细的自定义字段来描述事件信息, 以array对象存储在event_attr参数中。
