CY_GATE_HOST='http://gateway.serv.canyunwang2017.com'



# 酒店审核状态
    http -f $CY_GATE_HOST/bll_api/firm_apply/get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758
    返回
    msg:
        apply_not_exists  从未审核过
    msg:
        ok
            status:0 未审核  可继续提交审核
            status:1 审核通过
            status:-1 审核不通过  可继续提交审核
            status:-2 永久驳回  不可继续提交审核


# 酒店提交审核

    http -f $CY_GATE_HOST/bll_api/firm_apply/add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    name=康特小酒楼 \
    contract_name=张三 \
    contract_phone=13809522353 \
    addr=福建省福州市xx路 \
    firm_code=3545465454545 \
    firm_license="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2163440855,811056806&fm=27&gp=0.jpg" \
    shop_img="https://ss0.baidu.com/73t1bjeh1BF3odCf/it/u=3618234086,1199764508&fm=85&s=723059CBC636059E8315E91A03008093" \
    lat=64.1212312 \
    lng=119.1645646 \
    food_license="https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=4111742341,4038756735&fm=27&gp=0.jpg" \
    is_join_atv=1



# 被赠送列表
    http -f $CY_GATE_HOST/bll_api/firm/gift_list \
    acc=m13809522353 \
    user_token=23da8a2feb2a4fe87fc287468fe59893 \
    page=1 \
    page_size=10 \
    search=""

# 被赠送详情
    http -f $CY_GATE_HOST/bll_api/firm/gift_get \
    acc=m13809522353 \
    user_token=23da8a2feb2a4fe87fc287468fe59893 \
    gift_id=21


# 确认收货
    http -f $CY_GATE_HOST/bll_api/firm/gift_recv \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    gift_id=21

# 添加菜单/套餐
    http -f $CY_GATE_HOST/bll_api/firm/pkg_add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    name=满汉全席 \
    pkg_data='[
         {
             "goods_id":3,
             "num":1
         },
         {
             "goods_id":4,
             "num":3
         }
    ]'

# 菜单列表
    http -f $CY_GATE_HOST/bll_api/firm/pkg_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758
    search="s.status=0"

# 菜单详情
    http -f $CY_GATE_HOST/bll_api/firm/pkg_get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_id=3

# 删除菜单

    http -f $CY_GATE_HOST/bll_api/firm/pkg_del \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_id=5


# 修改菜单

    http -f $CY_GATE_HOST/bll_api/firm/pkg_reset \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_id=4 \
    name=满汉全席1 \
    pkg_data='[
         {
             "goods_id":4,
             "num":44
         },
         {
             "goods_id":3,
             "num":55
         }
    ]'

# 创建菜单政策
    pkg_id 菜单
    cate 分类先传0
    total_num 总桌数
    seat_num 座位数

    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_id=3 \
    name=中秋佳节免费试吃团 \
    intro=中秋佳节免费试吃,快来报名吧 \
    price=0 \
    cate=0 \
    seat_num=4 \
    total_num=10


# 删除菜单政策
    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_del \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_policy_id=4

# 菜单政策列表

    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    page=1 \
    page_size=1 \
    search=

# 获取菜单政策

    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_policy_id=5

# 修改菜单政策

    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_reset \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_policy_id=5 \
    pkg_id=3 \
    name=国庆套餐 \
    intro=国庆套餐,快来抢啊 \
    price=500 \
    cate=1 \
    seat_num=5 \
    sold_num=5 \
    total_num=5


# 抢座订单列表

    keyword可传用户手机号或抢座单号
    search可先不传

    http -f $CY_GATE_HOST/bll_api/firm/seat_order_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    keyword=13809522353 \
    search= \
    page=1 \
    page_size=10

# 抢座核销

    http -f $CY_GATE_HOST/bll_api/firm/seat_order_verify \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    seat_order_id=3


# 菜品列表
    http -f $CY_GATE_HOST/bll_api/firm/goods_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    search=""


# 订座首页
    http -f $CY_GATE_HOST/bll_api/firm/seat_order_index \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758

# 菜单政策的参与用户列表
    http -f $CY_GATE_HOST/bll_api/firm/pkg_policy_user_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    pkg_policy_id=5



