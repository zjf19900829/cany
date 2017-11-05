<?php
namespace modules\rest\controllers;
use model\GiftModel;

class gys {
    private $user_id;
    public static function getIns() {
        return new gys();
    }

    function __construct() {
        $this->user_id=get_user_id();
        //检查供应商的权限
        $ret=\Mdb::getIns()->get("gys_apply","*",[
            "user_id"=>$this->user_id
        ]);
        chk_db_err();
        if(!$ret){
            print_json(["code"=>1,"msg"=>"role_err","err_msg"=>"","info"=>"您还不是供应商,请提交申请"]);
        }
        if($ret['status']!=1){
            print_json(["code"=>1,"msg"=>"role_err","err_msg"=>"","info"=>"您还不是供应商,请耐心等待审核"]);
        }
    }

    function goods_list() {
        $data=$_REQUEST;
        $data["admin_key"]=CORE_ADMIN_KEY;
        $data["admin_sec"]=CORE_ADMIN_SEC;
        $data["search"]="{$_REQUEST['search']}&s.supplier_id={$this->user_id}&st.goods_id=0";
        $json=\httpRequest::post(CORE_HOST."/rest/goods/list",$data);
        $arr=json_decode($json,true);
        if($arr['msg']=='ok'){
            foreach($arr['result']['list'] as &$v){
                $v['num']=0;
                $v['create_time']=fmt_time($v['create_time']);

            }
        }
        print_json($arr);
    }
    function goods_add() {
        $need_fields=[
            "goods_name",
            "price",
            "cover",
            "imgs",
            "intro",
            "content",
            "cate_id",
        ];
        foreach($need_fields as $f){
            if(!$_REQUEST[$f]){
                print_json(["code"=>1,"msg"=>"need_field","err_msg"=>"{$f}_empty","info"=>"请完整填写表单信息"]);

            }
        }
        $ret=\Mdb::getIns()->insert("goods",[
            "supplier_id"=>$this->user_id,
            "goods_name"=>$_REQUEST['goods_name'],
            "price"=>$_REQUEST['price'],
            "cover"=>$_REQUEST['cover'],
            "imgs"=>$_REQUEST['imgs'],
            "intro"=>$_REQUEST['intro'],
            "content"=>$_REQUEST['content'],
            "create_time"=>time(),
            "status"=>0,
            "sort"=>0,
            "is_del"=>0,
            "cate_id"=>$_REQUEST['cate_id'],
        ]);
        chk_db_err();
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);

    }
    function goods_get() {
        $res = $this->__get_goods();
        print_json($res);

    }
    function goods_reset() {
        $res=$this->__get_goods();
        $data=[];
        $fields=[
            "goods_name",
            "price",
            "cover",
            "imgs",
            "intro",
            "content",
            "cate_id",
        ];
        foreach($fields as $f){
            $val=$_REQUEST[$f];
            if($val!=null){
                $data[$f]=$val;
            }
        }

        \Mdb::getIns()->update("goods",$data,[
            "goods_id"=>$res['result']['goods_id']
        ]);

        chk_db_err();
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);
    }
    function goods_del() {
        $this->__get_goods();

        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data['goods_id'] = intval($_REQUEST['goods_id']);
        $json = \httpRequest::post(CORE_HOST . "/rest/goods/del", $data);
        print_json_str($json);
    }
    //参与美食活动的活动的商家列表
    function join_atv_firm_list(){
        $data=$_REQUEST;
        $data["admin_key"]=CORE_ADMIN_KEY;
        $data["admin_sec"]=CORE_ADMIN_SEC;
        $data["search"]="{$_REQUEST['search']}&s.is_join_atv=1&s.status=1&st.firm_apply_id=0";
        $res=\httpRequest::post(CORE_HOST."/rest/firm_apply/list",$data);
        print_json_str($res);
    }
    
    //赠送列表
    function gift_list() {
        $data=$_REQUEST;
        $data["admin_key"]=CORE_ADMIN_KEY;
        $data["admin_sec"]=CORE_ADMIN_SEC;
        $data["search"]="{$_REQUEST['search']}&s.gys_id={$this->user_id}&st.gift_id=0";
        $res=json_decode(\httpRequest::post(CORE_HOST."/rest/gift/list",$data),true);
        if($res['msg']!="ok"){
            print_json($res);
        }

        foreach($res['result']['list'] as &$v){
            GiftModel::fmt_gift_info($v);
            $v['firm_info']=\Mdb::getIns()->get("firm_apply","*",[
                "user_id"=>$v['firm_id']
            ]);
            $v['send_time']=fmt_time($v['send_time']);
            $v['recv_time']=fmt_time($v['recv_time']);
        }
        print_json($res);
    }
    //赠送详情
    function gift_get(){
        if(!intval($_REQUEST['gift_id'])){
            print_json(["code"=>1,"msg"=>"gift_id_empty","err_msg"=>"","info"=>"赠送标识不能为空"]);
        }
        $gift=\Mdb::getIns()->get("gift","*",[
            "gift_id"=>$_REQUEST['gift_id'],
            "gys_id"=>$this->user_id
        ]);
        if(!$gift){
            print_json(["code"=>1,"msg"=>"gift_id_err","err_msg"=>"","info"=>"记录不存在"]);
        }
        $gift['firm_info']=\Mdb::getIns()->get("firm_apply","*",[
            "user_id"=>$gift['firm_id']
        ]);
        GiftModel::fmt_gift_info($gift);
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>$gift]);


    }
    //添加赠送
    function gift_add() {
        $need_fields=[
            "firm_id",
            "gift_data"
        ];
        foreach($need_fields as $f){
            if(!$_REQUEST[$f]){
                print_json(["code"=>1,"msg"=>"need_field","err_msg"=>"{$f}_empty","info"=>"请完整填写表单信息"]);
            }
        }
        //firm_id
        $firm=\Mdb::getIns()->get("user","*",[
            "user_id"=>$_REQUEST['firm_id']
        ]);
        if(!$firm){
            print_json(["code"=>1,"msg"=>"firm_id_err","err_msg"=>"","info"=>"该商家不存在"]);
        }
        $gift_data=json_decode($_REQUEST['gift_data'],true);
        if(!$gift_data){
            print_json(["code"=>1,"msg"=>"gift_data_err","err_msg"=>"gift_data_json_fmt_err","info"=>"赠送内容参数错误"]);
        }
        foreach($gift_data as $k=>$v){
            if(!intval($v['goods_id'])){
                print_json(["code"=>1,"msg"=>"gift_data_err","err_msg"=>"gift_data_goods_id_err:{$v['goods_id']}","info"=>"赠送内容参数错误"]);
            }
            //检测goods_id
            $goods_info=\Mdb::getIns()->get("goods","*",[
                "goods_id"=>intval($v['goods_id'])
            ]);
            if(!$goods_info){
                print_json(["code"=>1,"msg"=>"gift_data_err","err_msg"=>"gift_data_goods_info_not_exists:{$v['goods_id']}","info"=>"赠送内容参数错误"]);
            }
            if(!intval($v['num'])){
                print_json(["code"=>1,"msg"=>"gift_data_err","err_msg"=>"gift_data_num_err","info"=>"赠送内容参数错误"]);
            }
            $gift_data[$k]['goods_snapshot']=$goods_info;
        }
        $ret=\Mdb::getIns()->insert("gift",[
            "gys_id"=>$this->user_id,
            "firm_id"=>$_REQUEST['firm_id'],
            "create_time"=>time(),
            "send_time"=>time(),
            "recv_time"=>0,
            "status"=>0,
        ]);
        chk_db_err();
        $gift_id=\Mdb::getIns()->id();
        //写入gift2goods
        foreach($gift_data as $v){
            \Mdb::getIns()->insert("gift2goods",[
                "gift_id"=>$gift_id,
                "goods_id"=>$v['goods_id'],
                "num"=>$v['num'],
                "goods_snapshot"=>json_encode($v['goods_snapshot'],JSON_UNESCAPED_UNICODE)
            ]);
            chk_db_err();
        }

        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>null]);
    }

    /**
     * @return mixed
     */
    public function __get_goods() {
        $data = $_REQUEST;
        $data["admin_key"] = CORE_ADMIN_KEY;
        $data["admin_sec"] = CORE_ADMIN_SEC;
        $data['goods_id'] = intval($_REQUEST['goods_id']);
        if(!$data['goods_id']){
            print_json(["code"=>1,"msg"=>"goods_id_empty","err_msg"=>"","info"=>"商品标识不能为空"]);

        }
        $res = json_decode(\httpRequest::post(CORE_HOST . "/rest/goods/get", $data), true);
        if($res['msg']!="ok"){
            print_json($res);
        }

        if($res['result']['supplier_id']!=$this->user_id){
            print_json(["code"=>1,"msg"=>"goods_not_exists","err_msg"=>"","info"=>"商品不存在"]);
        }
        return $res;
    }

    //分类列表
    function goods_cate_list(){
        $list=\Mdb::getIns()->select("goods_cate","*");
        print_json(["code"=>0,"msg"=>"ok","info"=>"操作成功","result"=>[
            "list"=>$list,
            "rs_count"=>count($list),
        ]]);
    }

    

}