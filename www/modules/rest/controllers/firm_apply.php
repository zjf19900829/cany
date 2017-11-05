<?php
namespace modules\rest\controllers;
class firm_apply {
    public static function getIns() {
        return new firm_apply();
    }

    function get() {
        $user_id = get_user_id();
        $ret = \Mdb::getIns()->get("firm_apply", "*", [
            "user_id" => $user_id
        ]);
        if (!$ret) {
            print_json(["code" => 1, "msg" => "apply_not_exists", "err_msg" => "", "info" => "暂无申请记录"]);
        }
        print_json(["code" => 0, "msg" => "ok", "info" => "操作成功", "result" => $ret]);
    }

    function add() {
        $user_id = get_user_id();
        //已经提交
        $ret = \Mdb::getIns()->get("firm_apply", "*", [
            "user_id" => $user_id
        ]);
        //检查字段 todo

        $data= [
            "firm_apply_id"=>$user_id,
            "user_id"=>$user_id,
            "name"=>$_REQUEST['name'],
            "contract_name"=>$_REQUEST['contract_name'],
            "contract_phone"=>$_REQUEST['contract_phone'],
            "addr"=>$_REQUEST['addr'],
            "firm_code"=>$_REQUEST['firm_code'],
            "firm_license"=>$_REQUEST['firm_license'],
            "shop_img"=>$_REQUEST['shop_img'],
            "status"=>0,
            "lat"=>$_REQUEST['lat'],
            "lng"=>$_REQUEST['lng'],
            "create_time"=>time(),
            "approve_time"=>0,
            "reason"=>$_REQUEST['reason'],
            "food_license"=>$_REQUEST['food_license'],
            "is_join_atv"=>$_REQUEST['is_join_atv'],
        ];
        if (!$ret) {
            //写入数据库
            
             \Mdb::getIns()->insert("firm_apply",$data);
            chk_db_err();
            print_json(["code"=>0,"msg"=>"ok","info"=>"提交成功,请耐心等待审核","result"=>null]);

        }
        if($ret['status']==1){
            print_json(["code"=>1,"msg"=>"status_pass","err_msg"=>"","info"=>"您已通过审核了,无需重复审核"]);
        }
        if($ret['status']==-2){
            print_json(["code"=>1,"msg"=>"status_pass","err_msg"=>"","info"=>"提交申请被驳回"]);
        }


        //剩下就是0或者 -1  审核不通过

        $ret2 = \Mdb::getIns()->update("firm_apply",$data,[
            "firm_apply_id"=>$ret['firm_apply_id']
        ]);
        chk_db_err();

        print_json(["code" => 0, "msg" => "ok", "info" => "申请已提交,请耐心等待审核", "result" => null]);


    }

    function approve(){
        
        chk_admin();
        $status=intval($_REQUEST['status']);
        if(!in_array($status,[1,-1,-2])){
            print_json(["code"=>1,"msg"=>"params_err","err_msg"=>"status: 1|-1|-2","info"=>"参数错误"]);
        }
        if(in_array($status,[-1,-2]) && !$_REQUEST['reason']){
            print_json(["code"=>1,"msg"=>"reason_empty","err_msg"=>"","info"=>"请填写理由/修改意见"]);
        }
        $ret = \Mdb::getIns()->get("firm_apply", "*", [
            "firm_apply_id" => $_REQUEST['firm_apply_id']
        ]);
        if(!$ret){
            print_json(["code"=>1,"msg"=>"apply_not_exist","err_msg"=>"","info"=>"申请记录不存在"]);
        }
        //保存记录
        $ret=\Mdb::getIns()->update("firm_apply",[
            "status"=>$status,
            "reason"=>$_REQUEST['reason']
        ],[
            "firm_apply_id" => $_REQUEST['firm_apply_id']
        ]);
        chk_db_err();
        //推送
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);

    }

}