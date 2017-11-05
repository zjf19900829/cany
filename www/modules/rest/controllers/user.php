<?php
namespace modules\rest\controllers;

use model\PkgPolicyModel;
use model\SeatOrderModel;

class user {
       public static function getIns() {
        return new user();//什么作用,调用__construct()方法
    }

    function __construct() {
    $this->user_id = get_user_id();
}

    //抢座
    function rob_seat() {
        if (!$_REQUEST['pkg_policy_id']) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_err", "err_msg" => "", "info" => "菜单政策标识错误"]);
        }
        $policy_info = \Mdb::getIns()->get("pkg_policy", "*", [
            "pkg_policy_id" => $_REQUEST['pkg_policy_id']
        ]);
        if (!$policy_info) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_err", "err_msg" => "", "info" => "菜单政策不存在"]);
        }
        if ($policy_info['price'] > 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "该菜单政策不是美食分享"]);
        }
        //seat_num 先废弃    用total_num和sold_num来控制  这里只关注总的数量   至于座位怎么安排  那是后面的事
        if (($policy_info['total_num'] - $policy_info['sold_num']) <= 0) {
            print_json(["code" => 1, "msg" => "num_err", "err_msg" => "", "info" => "已经抢光了"]);
        }
        //是否已经抢过
        $seat_info = \Mdb::getIns()->get("seat_order", "*", [
            "pkg_policy_id" => $_REQUEST['pkg_policy_id'],
            "user_id" => $this->user_id
        ]);
        //有订单存在
        if ($seat_info) {
            print_json(["code" => 1, "msg" => "seat_info_exists", "err_msg" => "", "info" => "您已抢过了"]);
        }
        \Mdb::getIns()->insert("seat_order", [
            "user_id" => $this->user_id,
            "firm_id" => $policy_info['firm_id'],
            "create_time" => time(),
            "status" => 0,
            "pkg_policy_id" => $_REQUEST['pkg_policy_id'],
        ]);
        chk_db_err();
        $id = \Mdb::getIns()->id();
        //库存往上加
        //成功售出一件商品，往数据库的sold_num加上一件
        \Mdb::getIns()->update("pkg_policy", [
            "sold_num[+]" => 1
        ], [
            "pkg_policy_id" => $_REQUEST['pkg_policy_id']
        ]);
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => [
            "seat_order_id" => $id
        ]]);

    }

    //抢座订单列表
    function seat_order_list() {
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = $_REQUEST['search'] . "&s.user_id={$this->user_id}&st.seat_order_id=0";
        $json = \httpRequest::post(CORE_HOST . "/rest/seat_order/list", $data);
        $res = json_decode($json, true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            SeatOrderModel::fmt_info($v);
            $v['create_time']=fmt_time($v['create_time']);
        }
        print_json($res);

    }

    //抢座订单详情
    function seat_order_get() {
        if (!$_REQUEST['seat_order_id']) {
            print_json(["code" => 1, "msg" => "seat_order_id_emtpy", "err_msg" => "", "info" => "抢座订单标识错误"]);
        }
        $info = \Mdb::getIns()->get("seat_order", "*", [
            "seat_order_id" => $_REQUEST['seat_order_id'],
            "user_id" => $this->user_id
        ]);
        if (!$info) {
            print_json(["code" => 1, "msg" => "seat_order_id_err", "err_msg" => "", "info" => "抢座订单标识错误"]);
        }
        SeatOrderModel::fmt_info($info);
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => $info]);

    }
    
    //绑定微信
    function bind_weixin(){

        $need_fields=[
            "wx_openid",
            "nickname",
            "face",
        ];
        foreach($need_fields as $f){
            if(!$_REQUEST[$f]){
                print_json(["code"=>1,"msg"=>"need_field","err_msg"=>"{$f}_empty","info"=>"请完整填写表单信息"]);

            }
        }
        $info=\Mdb::getIns()->get("user","*",[
            "wx_openid"=>$_POST['wx_openid']
        ]);
        if(!$info || $info['user_id']==$this->user_id){
            \Mdb::getIns()->update("user",[
                "nickname"=>$_POST['nickname'],
                "face"=>$_POST['face'],
                "sex"=>$_POST['sex'],
                "wx_openid"=>$_POST['wx_openid'],
            ],[
                "user_id"=>$this->user_id
            ]);
            chk_db_err();
            print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);

        }else{
            print_json(["code"=>1,"msg"=>"bind_err","err_msg"=>"","info"=>"该微信号已绑定其他账号"]);
        }
    }

    //js_code换openid
    function jscode2openid(){
        //js_code appid
        echo \httpRequest::post(XCX_LOGIN_HOST."/rest/jscode2openid/index",$_POST);
    } 


    //买下一桌
    function buy(){
        
    }


    //商家列表
    function firm_list(){

        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = $_REQUEST['search'];
        $json = \httpRequest::post(CORE_HOST . "/rest/firm_apply/list", $data);
        $res = json_decode($json, true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            $v['firm_id']=$v['user_id'];
        }
        print_json($res);
    }//


    function goods_cate_list(){
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $json = \httpRequest::post(CORE_HOST . "/rest/goods_cate/list", $data);
        $res = json_decode($json, true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        print_json($res);
    }


/*function goods_list(){

    $data = $_REQUEST;
    $data["admin_key"] = CORE_ADMIN_KEY;
    $data["admin_sec"] = CORE_ADMIN_SEC;
    $json = \httpRequest::post(CORE_HOST . "/rest/goods/list", $data);
    $res = json_decode($json, true);
    //是否判断数据的status是否不合格，不合格的数据是否要过滤掉

}*/

    function goods_list() {
        $data=$_REQUEST;
        $data["admin_key"]=CORE_ADMIN_KEY;
        $data["admin_sec"]=CORE_ADMIN_SEC;
        $data["search"]="{$_REQUEST['search']}&st.goods_id=0";//&st.goods_id=0 倒序
        $json=\httpRequest::post(CORE_HOST."/rest/goods/list",$data);
        $arr=json_decode($json,true);
        if($arr['msg']=='ok'){
            foreach($arr['result']['list'] as &$v){
                $v['num']=0;//前端要求这样弄
                $v['create_time']=fmt_time($v['create_time']);//时间格式转化

            }
        }
        print_json($arr);
    }








}