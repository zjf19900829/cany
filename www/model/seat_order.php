<?php
namespace model;
class SeatOrderModel {

    const STATUS_CREATED = 0;
    const STATUS_VERIFYED = 1;

    static function fmt_info(&$info) {
        $user_info=\Mdb::getIns()->get("user","*",[
            "user_id"=>$info['user_id']
        ]);
        $firm_apply_info=\Mdb::getIns()->get("firm_apply","*",[
            "user_id"=>$info['user_id']
        ]);
        $pkg_policy_info=\Mdb::getIns()->get("pkg_policy","*",[
            "pkg_policy_id"=>$info['pkg_policy_id']
        ]);
        $pkg_policy_info['dinner_time']=fmt_time($pkg_policy_info['dinner_time']);
        $pkg_policy_info['create_time']=fmt_time($pkg_policy_info['create_time']);
        
        $pkg_info=\Mdb::getIns()->get("pkg","*",[
            "pkg_id"=>$pkg_policy_info['pkg_id']
        ]);

        PkgModel::fmt_info($pkg_info);
        
        $info['user_info']=$user_info;
        $info['firm_info']=$firm_apply_info;
        $info['pkg_policy_info']=$pkg_policy_info;
        $info['pkg_info']=$pkg_info;

        //二维码 ?txt=111
        $info['qrcode']=QRCODE_HOST."?size=150&txt=".urlencode(json_encode([
                "order_type"=>"seat_order",
                "order_id"=>$info['seat_order_id'],
                "user_id"=>$info['user_id'],
                "pkg_policy_id"=>$info['pkg_policy_id'],
            ]));
    }
    static function fmt_user_info(&$info) {
        $user_info=\Mdb::getIns()->get("user","*",[
            "user_id"=>$info['user_id']
        ]);
        $info['user_info']=$user_info;
    }

    static function get_chk_pkg($seat_order_id, $user_id) {
       
    }

    
}