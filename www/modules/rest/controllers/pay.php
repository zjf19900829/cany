<?php
namespace modules\rest\controllers;

use OSS\Core\OssException;
use OSS\OssClient;

class pay {

    public static function getIns() {
        return new pay();
    }

    function __construct() {
    }
    //接受 PAY_HOST="http://canyun-pay.serv.canyunwang2017.com"的通知
    /*
    #小程序支付回调
    http -f $BLL_HOST/rest/pay/notify \
    X-hengcheng-gateway:1 \
    key=hc \
    sec=hc123123 \
    order_no=20170620151151
    */
    //根据不同的订单类型去处理
    function notify() {
        


    }


}