<?php
namespace modules\rest\controllers;

use model\GiftModel;
use model\PkgModel;
use model\PkgPolicyModel;
use model\SeatOrderModel;

class firm {
    private $user_id;

    public static function getIns() {
        return new firm();
    }

    function __construct() {
        $this->user_id = get_user_id();
        //检查酒店的权限
        $ret = \Mdb::getIns()->get("firm_apply", "*", [
            "user_id" => $this->user_id
        ]);
        chk_db_err();
        if (!$ret) {
            print_json(["code" => 1, "msg" => "role_err", "err_msg" => "", "info" => "您还不是商家,请提交申请"]);
        }
        if ($ret['status'] != 1) {
            print_json(["code" => 1, "msg" => "role_err", "err_msg" => "", "info" => "您还不是商家,请耐心等待审核"]);
        }
    }


    //被赠送列表
    function gift_list() {
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = "{$_REQUEST['search']}&s.firm_id={$this->user_id}&st.firm_id=0";
        $res = json_decode(\httpRequest::post(CORE_HOST . "/rest/gift/list", $data), true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            GiftModel::fmt_gift_info($v);
//
            $v['gys_info'] = \Mdb::getIns()->get("gys_apply", "*", [
                "user_id" => $v['gys_id']
            ]);
            $v['send_time']=fmt_time($v['send_time']);
            $v['recv_time']=fmt_time($v['recv_time']);
        }

        print_json($res);
    }

    //赠送详情
    function gift_get() {
        $gift = GiftModel::get_chk_gift($_REQUEST['gift_id'], $this->user_id);

        $gift['gys_info'] = \Mdb::getIns()->get("gys_apply", "*", [
            "user_id" => $gift['gys_id']
        ]);
        GiftModel::fmt_gift_info($gift);
        $gift['send_time']=fmt_time($gift['send_time']);
        $gift['recv_time']=fmt_time($gift['recv_time']);
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => $gift]);


    }

    //赠送确认收货
    function gift_recv() {
        $gift = GiftModel::get_chk_gift($_REQUEST['gift_id'], $this->user_id);
        if ($gift['status'] == GiftModel::STATUS_RECVED) {
            print_json(["code" => 1, "msg" => "recved", "err_msg" => "", "info" => "已确认收货"]);
        }

        \Mdb::getIns()->update("gift", [
            "status" => GiftModel::STATUS_RECVED,
            "recv_time" => time()
        ], [
            "gift_id" => $gift['gift_id']
        ]);
        chk_db_err();
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => null]);

    }
    //菜单列表
    function pkg_list() {
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = "{$_REQUEST['search']}&s.firm_id={$this->user_id}&st.pkg_id=0";
        $res = json_decode(\httpRequest::post(CORE_HOST . "/rest/pkg/list", $data), true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            PkgModel::fmt_info($v);
            $v['create_time']=fmt_time($v['create_time']);
        }
        print_json($res);
    }

    //添加套餐
    function pkg_add() {
        $need_fields = [
            "name",
            "pkg_data"
        ];
        foreach ($need_fields as $f) {
            if (!$_REQUEST[$f]) {
                print_json(["code" => 1, "msg" => "need_field", "err_msg" => "{$f}_empty", "info" => "请完整填写表单信息"]);
            }
        }

        $pkg_data = json_decode($_REQUEST['pkg_data'], true);
        if (!$pkg_data) {
            print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_json_fmt_err", "info" => "菜单内容参数错误"]);
        }
        foreach ($pkg_data as $k => $v) {
            if (!intval($v['goods_id'])) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_goods_id_err:{$v['goods_id']}", "info" => "菜单内容参数错误"]);
            }
            //检测goods_id
            $goods_info = \Mdb::getIns()->get("goods", "*", [
                "goods_id" => intval($v['goods_id'])
            ]);
            if (!$goods_info) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_goods_info_not_exists:{$v['goods_id']}", "info" => "菜单内容参数错误"]);
            }

            if (!intval($v['num'])) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_num_err", "info" => "菜单内容参数错误"]);
            }


            $pkg_data[$k]['goods_snapshot'] = $goods_info;
        }
        $ret = \Mdb::getIns()->insert("pkg", [
            "name" => $_REQUEST['name'],
            "data" => "",
            "create_time" => time(),
            "status" => 0,
            "firm_id" => $this->user_id,
        ]);
        chk_db_err();
        $pkg_id = \Mdb::getIns()->id();
        //写入pkg2goods
        foreach ($pkg_data as $v) {
            \Mdb::getIns()->insert("pkg2goods", [
                "pkg_id" => $pkg_id,
                "goods_id" => $v['goods_id'],
                "num" => $v['num'],
                "goods_snapshot" => json_encode($v['goods_snapshot'], JSON_UNESCAPED_UNICODE)
            ]);
            chk_db_err();
        }

        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => null]);
    }
    //获取套餐
    function pkg_get() {
        if (!$_POST['pkg_id']) {
            print_json(["code" => 1, "msg" => "pkg_id_empty", "err_msg" => "", "info" => "菜单标识不能为空"]);
        }
        $pkg_info = PkgModel::get_chk_pkg($_POST['pkg_id'], $this->user_id);
        PkgModel::fmt_info($pkg_info);
        $pkg_info['create_time']=fmt_time($pkg_info['create_time']);
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => $pkg_info]);


    }
    //套餐修改
    function pkg_reset() {
        $need_fields = [
            "pkg_id",
            "name",
            "pkg_data"
        ];
        foreach ($need_fields as $f) {
            if (!$_REQUEST[$f]) {
                print_json(["code" => 1, "msg" => "need_field", "err_msg" => "{$f}_empty", "info" => "请完整填写表单信息"]);
            }
        }
        $pkg_info = PkgModel::get_chk_pkg($_POST['pkg_id'], $this->user_id);

        $pkg_data = json_decode($_REQUEST['pkg_data'], true);
        if (!$pkg_data) {
            print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_json_fmt_err", "info" => "菜单内容参数错误"]);
        }
        foreach ($pkg_data as $k => $v) {
            if (!intval($v['goods_id'])) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_goods_id_err:{$v['goods_id']}", "info" => "菜单内容参数错误"]);
            }
            //检测goods_id
            $goods_info = \Mdb::getIns()->get("goods", "*", [
                "goods_id" => intval($v['goods_id'])
            ]);
            if (!$goods_info) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_goods_info_not_exists:{$v['goods_id']}", "info" => "菜单内容参数错误"]);
            }

            if (!intval($v['num'])) {
                print_json(["code" => 1, "msg" => "pkg_data_err", "err_msg" => "pkg_data_num_err", "info" => "菜单内容参数错误"]);
            }


            $pkg_data[$k]['goods_snapshot'] = $goods_info;
        }
        $ret = \Mdb::getIns()->update("pkg", [
            "name" => $_REQUEST['name'],
            "data" => "",
            "create_time" => time(),
            "status" => 0,
            "firm_id" => $this->user_id,
        ],[
            "pkg_id"=>$_POST['pkg_id']
        ]);

        chk_db_err();
        //删除旧的
        \Mdb::getIns()->delete("pkg2goods", [
            "pkg_id" => $_POST['pkg_id'],
        ]);
        chk_db_err();
        //写入pkg2goods
        foreach ($pkg_data as $v) {
            \Mdb::getIns()->insert("pkg2goods", [
                "pkg_id" => $_POST['pkg_id'],
                "goods_id" => $v['goods_id'],
                "num" => $v['num'],
                "goods_snapshot" => json_encode($v['goods_snapshot'], JSON_UNESCAPED_UNICODE)
            ]);
            chk_db_err();
        }

        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => null]);
    }
    //删除套餐
    function pkg_del() {
        if (!$_POST['pkg_id']) {
            print_json(["code" => 1, "msg" => "pkg_id_empty", "err_msg" => "", "info" => "菜单标识不能为空"]);
        }
        $pkg_info = PkgModel::get_chk_pkg($_POST['pkg_id'], $this->user_id);
        \Mdb::getIns()->delete("pkg", [
            "pkg_id" => $_POST['pkg_id']
        ]);
        chk_db_err();
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => null]);

    }
   

    //添加政策
    function pkg_policy_add() {
        $need_fields = [
            "pkg_id",
            "name",
            "intro",
        ];
        foreach ($need_fields as $f) {
            if (!$_REQUEST[$f]) {
                print_json(["code" => 1, "msg" => "need_field", "err_msg" => "{$f}_empty", "info" => "请完整填写表单信息"]);
            }
        }
        PkgModel::get_chk_pkg($_POST['pkg_id'], $this->user_id);

        $price = intval($_POST['price']);
        $seat_num = intval($_POST['seat_num']);
        $total_num = intval($_POST['total_num']);
        if ($price < 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "价格不能小于0"]);
        }
        if ($seat_num < 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "座位数不能小于0"]);
        }
        if ($total_num < 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "桌数不能小于0"]);
        }
        \Mdb::getIns()->insert("pkg_policy", [
            "name" => $_REQUEST['name'],
            "intro" => $_REQUEST['intro'],
            "pkg_id" => $_REQUEST['pkg_id'],
            "firm_id" => $this->user_id,
            "price" => $_REQUEST['price'],
            "create_time" => time(),
            "cate" => $_REQUEST['cate'] ?: 1,
            "total_num" => $total_num,
            "sold_num" => 0,
            "status" => 0,
            "seat_num" => $seat_num,
        ]);
        chk_db_err();
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => [
            "pkg_policy_id" => \Mdb::getIns()->id()
        ]]);

    }
    //删除政策
    function pkg_policy_del(){
        if (!$_POST['pkg_policy_id']) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_empty", "err_msg" => "", "info" => "菜单政策标识不能为空"]);
        }
        $info=PkgPolicyModel::get_chk_pkg_policy($_POST['pkg_policy_id'], $this->user_id);
        \Mdb::getIns()->delete("pkg_policy",[
            "pkg_policy_id"=>$_POST['pkg_policy_id']    
        ]);
        chk_db_err();
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);

    }
    //修改政策
    function pkg_policy_reset(){
        $need_fields = [
            "pkg_policy_id",
            "pkg_id",
            "name",
            "intro",
//            "price",
            "seat_num",
            "total_num",
        ];
        foreach ($need_fields as $f) {
            if (!$_REQUEST[$f]) {
                print_json(["code" => 1, "msg" => "need_field", "err_msg" => "{$f}_empty", "info" => "请完整填写表单信息"]);
            }
        }
        //检查政策
        PkgPolicyModel::get_chk_pkg_policy($_POST['pkg_policy_id'], $this->user_id);
        //检查菜单
        PkgModel::get_chk_pkg($_POST['pkg_id'], $this->user_id);

        $price = intval($_POST['price']);
        $seat_num = intval($_POST['seat_num']);
        $total_num = intval($_POST['total_num']);
        $sold_num = intval($_POST['sold_num']);
        if ($price < 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "价格不能小于0"]);
        }
//        if ($seat_num < 0) {
//            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "座位数不能小于0"]);
//        }
        if ($total_num < 0) {
            print_json(["code" => 1, "msg" => "price_err", "err_msg" => "", "info" => "桌数不能小于0"]);
        }
        if ($sold_num < 0) {
            print_json(["code" => 1, "msg" => "sold_err", "err_msg" => "", "info" => "已售不能小于0"]);
        }
        \Mdb::getIns()->update("pkg_policy", [
            "name" => $_REQUEST['name'],
            "intro" => $_REQUEST['intro'],
            "pkg_id" => $_REQUEST['pkg_id'],
            "firm_id" => $this->user_id,
            "price" => $_REQUEST['price'],
            "create_time" => time(),
            "cate" => $_REQUEST['cate'] ?: 1,
            "total_num" => $_REQUEST['total_num'],
            "sold_num" =>  $_REQUEST['sold_num'],
            "seat_num" =>  $_REQUEST['seat_num'],
            "status" => 0,
        ],[
            "pkg_policy_id"=>$_REQUEST['pkg_policy_id']
        ]);
        chk_db_err();
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => null]);
    }
    //政策列表
    function pkg_policy_list(){
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = "{$_REQUEST['search']}&s.firm_id={$this->user_id}&st.pkg_policy_id=0";
        $json = \httpRequest::post(CORE_HOST . "/rest/pkg_policy/list", $data);
        $res = json_decode($json, true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            PkgPolicyModel::fmt_info($v);
            $v['create_time']=fmt_time($v['create_time']);
            $v['dinner_time']=fmt_time($v['dinner_time']);
            $v['start_time']=fmt_time($v['start_time']);
            $v['end_time']=fmt_time($v['end_time']);
        }
        print_json($res);
    }
    //政策查询
    function pkg_policy_get(){
        if(!$_POST['pkg_policy_id']) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_empty", "err_msg" => "", "info" => "菜单政策标识错误"]);
        }
        $pkg_policy_info=PkgPolicyModel::get_chk_pkg_policy($_POST['pkg_policy_id'], $this->user_id);
        PkgPolicyModel::fmt_info($pkg_policy_info);

        $pkg_policy_info['create_time']=fmt_time($pkg_policy_info['create_time']);
        $pkg_policy_info['dinner_time']=fmt_time($pkg_policy_info['dinner_time']);
        $pkg_policy_info['start_time']=fmt_time($pkg_policy_info['start_time']);
        $pkg_policy_info['end_time']=fmt_time($pkg_policy_info['end_time']);

        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>$pkg_policy_info]);


    }
    //查询抢座订单 用户手机号/抢座订单号
    function seat_order_list(){
        $keyword=$_REQUEST['keyword'];
        $core_search="&s.firm_id={$this->user_id}&st.seat_order_id=0";
        if(strlen($keyword)==11){
            //手机号
            $user_info=\Mdb::getIns()->get("user","*",[
                "mobile"=>$keyword
            ]);
            chk_db_err();
            if(!$user_info){
                print_json(["code"=>1,"msg"=>"db_err","err_msg"=>"","info"=>"未找到相关用户"]);
            }
            $core_search.="&s.user_id={$user_info['user_id']}";
        }else if(intval($keyword)){
            //订单号
            $core_search.="&s.seat_order_id=".intval($keyword);
        }
        $data=$_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = $_REQUEST['search'].$core_search;
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
    //核销抢座订单  标记成已用餐
    function seat_order_verify(){
        $info=\Mdb::getIns()->get("seat_order","*",[
           "seat_order_id"=>$_REQUEST['seat_order_id'] ,
            "firm_id"=>$this->user_id
        ]);
        if(!$info){
            print_json(["code"=>1,"msg"=>"seat_order_err","err_msg"=>"","info"=>"抢座订单不存在"]);
        }
        if($info['status']!=SeatOrderModel::STATUS_CREATED){
            print_json(["code"=>1,"msg"=>"该抢座订单已核销","err_msg"=>"","info"=>""]);
        }
        //进行核销
        \Mdb::getIns()->update("seat_order",[
            "status"=>SeatOrderModel::STATUS_VERIFYED
        ],[
            "seat_order_id"=>$_REQUEST['seat_order_id'] ,
        ]);
        chk_db_err();
        print_json(["code"=>0,"msg"=>"ok","info"=>"核销成功","result"=>null]);
        
        
    }

    //  返回自己购买的食材列表和被赠送的食材列表
    //todo 这里先不做成品菜和食材的区分

    function goods_list() {
        $data=$_REQUEST;
        $data["admin_key"]=CORE_ADMIN_KEY;
        $data["admin_sec"]=CORE_ADMIN_SEC;
        $data["search"]="{$_REQUEST['search']}";
        $json=\httpRequest::post(CORE_HOST."/rest/goods/list",$data);
        $arr=json_decode($json,true);
        if($arr['msg']=='ok'){
            foreach($arr['result']['list'] as &$v){
                $v['num']=1;
                $v['create_time']=fmt_time($v['create_time']);

            }
        }
        print_json($arr);
    }

    # 订座首页
    function seat_order_index(){
        //todo
        $user_num=\Mdb::getIns()->count("seat_order",[
            "firm_id"=>$this->user_id
        ]);
        $pkg_policy_num=\Mdb::getIns()->count("pkg_policy",[
            "firm_id"=>$this->user_id
        ]);
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>[

            "rob_seat"=>[
                "user_num"=>$user_num,
                "pkg_policy_num"=>$pkg_policy_num
            ],
        ]]);

    }

    # 菜单政策的参与用户列表
    function pkg_policy_user_list(){
        //pkg_policy_id
        $pkg_policy_id=intval($_REQUEST['pkg_policy_id']);
        if(!$pkg_policy_id){
            print_json(["code"=>1,"msg"=>"pkg_policy_id_empty","err_msg"=>"","info"=>"请选择菜单政策"]);
        }
        
        $core_search="&s.firm_id={$this->user_id}&s.pkg_policy_id={$pkg_policy_id}&st.seat_order_id=0";
        
       
        $data=$_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data["search"] = $_REQUEST['search'].$core_search;
        $json = \httpRequest::post(CORE_HOST . "/rest/seat_order/list", $data);
        $res = json_decode($json, true);
        if ($res['msg'] != "ok") {
            print_json($res);
        }

        foreach ($res['result']['list'] as &$v) {
            SeatOrderModel::fmt_user_info($v);
            $v['create_time']=fmt_time($v['create_time']);
        }
        print_json($res);
    }

}