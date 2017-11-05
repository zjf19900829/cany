<?php
//
//use OSS\OssClient;
//use OSS\Core\OssException;
//
class BooisLog {
//    public static $host = "http://log.fodoco.com/log/record";
//
//    public static function record($name, $value, $app = null) {
//        $tag=($app ? $app."_".$name: $name);
//        self::ossLog($tag,$value);
//    }
//
//    static function post($url, $post = null, $header = null) {
//        $ch = curl_init();
//        $header = $header or array();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//        $result = curl_exec($ch);
//        curl_close($ch);
//        return $result;
//    }
//
//    static function get_client_ip() {
//        if ($_SERVER['REMOTE_ADDR']) {
//            $cip = $_SERVER['REMOTE_ADDR'];
//        } elseif (getenv("REMOTE_ADDR")) {
//            $cip = getenv("REMOTE_ADDR");
//        } elseif (getenv("HTTP_CLIENT_IP")) {
//            $cip = getenv("HTTP_CLIENT_IP");
//        } else {
//            $cip = "unknown";
//        }
//        return $cip;
//    }
//
//    static function mail($txt, $to = "api") {
//
//        self::ossLog($to,$txt);
//
//    }
//
    public static function ossLog($tag, $txt) {
//        /*
//        curl  http://xuepin-main.oss-cn-hangzhou.aliyuncs.com/log/2017-05-08/test.log
//        */
//        $txt=date("[H:i:s] ").$txt;
//        try {
//            $ossClient = new OssClient(OSS_KEY, OSS_SEC, OSS_END_POINT);
//        } catch (OssException $e) {
////            $e->getMessage();
//            return;
//        }
//
//        $object = "log/" . date("Y-m-d") . "/" . $tag . ".log";
////        var_dump($object);
//        try {
//            $meta = $ossClient->getObjectMeta(OSS_BUCKET, $object);
//            $pos = intval($meta['x-oss-next-append-position']);
//        } catch (OssException $e) {
//            $pos = 0;
//        }
//
//        try {
////            exit;
//            $position = $ossClient->appendObject(OSS_BUCKET, $object, $txt . "\n\n", $pos);
//        } catch (OssException $e) {
////            var_dump($e);
//            return;
//        }
    }
}