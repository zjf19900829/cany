<?php
namespace model;
class PkgPolicyModel {

    static function fmt_info(&$pkg_policy_info) {
        $pkg_info=\Mdb::getIns()->get("pkg","*",[
            "pkg_id" =>$pkg_policy_info['pkg_id']
        ]);
        chk_db_err();
        PkgModel::fmt_info($pkg_info);
        $pkg_policy_info['pkg_info'] = $pkg_info;
    }

    static function get_chk_pkg_policy($pkg_policy_id, $user_id) {
        if (!intval($pkg_policy_id)) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_empty", "err_msg" => "", "info" => "菜单政策标识不能为空"]);
        }
        $pkg = \Mdb::getIns()->get("pkg_policy", "*", [
            "pkg_policy_id" => $_REQUEST['pkg_policy_id'],
            "firm_id" => $user_id
        ]);
        if (!$pkg) {
            print_json(["code" => 1, "msg" => "pkg_policy_id_err", "err_msg" => "", "info" => "记录不存在"]);
        }
        return $pkg;
    }

    
}