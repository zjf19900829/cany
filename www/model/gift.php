<?php
namespace model;
class GiftModel {
    const STATUS_CREATED=0;
    const STATUS_SENDED=1;
    const STATUS_RECVED=2;
    
    static function fmt_gift_info(&$gift_info) {
        $gift2goods_list = \Mdb::getIns()->select("gift2goods", "*", [
            "gift_id" => $gift_info['gift_id']
        ]);
        chk_db_err();
        if (!$gift2goods_list) {
            return;
        }

        foreach ($gift2goods_list as &$gift2goods) {
            $gift2goods['goods_snapshot'] = json_decode($gift2goods['goods_snapshot'], true);
        }


        $gift_info['gift_data'] = $gift2goods_list;
    }

    static function get_chk_gift($gift_id, $user_id) {
        if (!intval($gift_id)) {
            print_json(["code" => 1, "msg" => "gift_id_empty", "err_msg" => "", "info" => "赠送标识不能为空"]);
        }
        $gift = \Mdb::getIns()->get("gift", "*", [
            "gift_id" => $_REQUEST['gift_id'],
            "firm_id" => $user_id
        ]);
        if (!$gift) {
            print_json(["code" => 1, "msg" => "gift_id_err", "err_msg" => "", "info" => "记录不存在"]);
        }
        return $gift;
    }
    //todo  暂不做被赠送剩余数量的检查
    static function chk_goods_num($user_id,$goods_id){
        return true;
    }
}