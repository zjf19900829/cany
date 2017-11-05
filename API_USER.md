CY_GATE_HOST=http://canyun-gateway.serv.boois.cn
CY_GATE_HOST=http://gateway.serv.canyunwang2017.com



#  抢座
    http -f $CY_GATE_HOST/bll_api/user/rob_seat \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_policy_id=5


# 抢座订单列表
    http -f $CY_GATE_HOST/bll_api/user/seat_order_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    page=1 \
    page_size=1

# 抢座订单详情
    http -f $CY_GATE_HOST/bll_api/user/seat_order_get \
    acc=m13809522353 \
    user_token=23da8a2feb2a4fe87fc287468fe59893 \
    seat_order_id=3

# 绑定微信信息
    http -f $CY_GATE_HOST/bll_api/user/bind_weixin \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    nickname=上官 \
    face="https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=2974104803,1439396293&fm=200&gp=0.jpg" \
    wx_openid="af12312313" \
    sex=1


# js_code换openid

    用户端appid:wx95a89fbbd14aa174
    商家端appid:wxce80518d723e599e

    http -f $CY_GATE_HOST/bll_api/user/jscode2openid \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    appid=wxce80518d723e599e \
    js_code=021sHoOs1FqB7p0SUuLs1DXhOs1sHoOC

    返回:
    {
        "Raw": null,
        "code": 0,
        "info": "操作成功",
        "msg": "ok",
        "raw": null,
        "result": {
            "openid": "oavkP0QfqpOKb6UMISdRPdjmNVTQ",
            "session_key": "LR3cOg9r9JxNP6pqcZw7hw==",
            "unionid": null
        }
    }
# 商家列表

    http -f $CY_GATE_HOST/bll_api/user/firm_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    page=1 \
    page_size=10 \
    search=""



# 商品分类列表
    http -f $CY_GATE_HOST/bll_api/user/goods_cate_list \
    acc=m13809522353 \
    user_token=23da8a2feb2a4fe87fc287468fe59893 
  
  
 #商品列表   
    http -f $CY_GATE_HOST/bll_api/user/goods_list \
    acc=m13809522353 \
    user_token=23da8a2feb2a4fe87fc287468fe59893 
    
 
 
 
 
 
 
 
 
 
 
 
 
 
 