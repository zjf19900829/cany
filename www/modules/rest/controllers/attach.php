<?php
namespace modules\rest\controllers;

use OSS\Core\OssException;
use OSS\OssClient;

class attach {

    public static function getIns() {
        return new attach();
    }

    function __construct() {
    }

    function upload() {
//		var_dump($_POST);
//		var_dump($_FILES['file']['tmp_name']);
        try {
            $ossClient = new OssClient(OSS_KEY, OSS_SEC, OSS_END_POINT);
        } catch (OssException $e) {
            print_json(["code" => 1, "msg" => "upload_err", "err_msg" => $e->getTraceAsString(), "info" => "上传失败"]);

        }

        $ext = $_POST['ext'] ?: "jpg";
        $object = "attach/".date("Y/m/d/"). (new \Guid())->toString1() . "." . $ext;
//        var_dump($object);
        try {
            $meta = $ossClient->uploadFile(OSS_BUCKET, $object, $_FILES['file']['tmp_name']);
//			$url=$ossClient->signUrl(OSS_BUCKET, $object);
            //http://hc-main.oss-cn-hangzhou.aliyuncs.com/tmp/attach/FFE3DB8750D4A32A36F30E3E56FA98A3.jpg
            $url = "http://" . OSS_BUCKET . "." . OSS_END_POINT . "/" . $object;
            print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => [
                "url" => $url
            ]]);
        } catch (Exception $e) {
            print_json(["code" => 1, "msg" => "upload_err", "err_msg" => $e->getTraceAsString(), "info" => "上传失败"]);

        }
    }


}