<?php
function app_init() {
    error_reporting(E_ERROR);
    date_default_timezone_set("PRC");
    new Mdb(MYSQL_DB, MYSQL_HOST, MYSQL_USER, MYSQL_PSW, intval(MYSQL_PORT));
}
function chk_db_err(){
    if(! \Mdb::getIns()->isOk()){
        print_json(["code"=>1,"msg"=>"db_err","err_msg"=>\Mdb::getIns()->error(),"info"=>"数据库异常"]);
        
    }
}
function print_json_str($str) {
    ob_clean();
    header("Access-Control-Allow-Origin:*");
    header("Content-type: application/json; charset=utf-8");
    echo $str;
    exit();
}

function print_json($arr) {

    $str = json_encode($arr, JSON_UNESCAPED_UNICODE);
    print_json_str($str);
}

function get_user_id() {
    $json = httpRequest::post(UC_HOST . "/rest/user/chk", [
        "acc" => $_POST['acc'],
        "user_token" => $_POST['user_token'],
    ]);
    $arr = json_decode($json, true);

    if ($arr['msg'] != "ok") {
        print_json(["code" => 1, "msg" => "token_err", "err_msg" => $arr['msg'], "info" => "请重新登录"]);
    }

    $ret = Mdb::getIns()->get("user", "*", [
        "acc" => $_POST['acc']
    ]);
    chk_db_err();
    $user_id = $ret['user_id'];
    if (!$user_id) {
        print_json(["code" => 1, "msg" => "user_not_exists", "err_msg" => "", "info" => "用户不存在"]);
    }
    return $user_id;
}

//管理员权验证
function chk_admin() {
    if ($_REQUEST['admin_key'] != "admin" || $_REQUEST['admin_sec'] != "admin123123") {
        print_json(["code" => 1, "msg" => "admin_key_sec_err", "err_msg" => "", "info" => "接口权限错误"]);

    }
}

//格式化时间

function fmt_time($stamp){
    return date("Y-m-d H:i:s",$stamp);
}
function fmt_date($stamp){
    return date("Y-m-d",$stamp);
}

