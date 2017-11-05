<?php
namespace model;
class PkgModel {

    static function fmt_info(&$pkg_info) {
        $pkg2goods_list = \Mdb::getIns()->select("pkg2goods", "*", [
            "pkg_id" => $pkg_info['pkg_id']
        ]);

        chk_db_err();
        if (!$pkg2goods_list) {
            $pkg_info['pkg_data'] =[];
            return;
        }

        foreach ($pkg2goods_list as &$pkg2goods) {
            $pkg2goods['goods_snapshot'] = json_decode($pkg2goods['goods_snapshot'], true);
        }

        $pkg_info['pkg_data'] = $pkg2goods_list;
    }

    static function get_chk_pkg($pkg_id, $user_id) {
        if (!intval($pkg_id)) {
            print_json(["code" => 1, "msg" => "pkg_id_empty", "err_msg" => "", "info" => "菜单标识不能为空"]);
        }
        $pkg = \Mdb::getIns()->get("pkg", "*", [
            "pkg_id" => $_REQUEST['pkg_id'],
            "firm_id" => $user_id
        ]);
        if (!$pkg) {
            print_json(["code" => 1, "msg" => "pkg_id_err", "err_msg" => "", "info" => "记录不存在"]);
        }
        return $pkg;
    }

    
}