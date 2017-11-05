CY_GATE_HOST=http://canyun-gateway.serv.boois.cn

# 供应审核状态

    http -f $CY_GATE_HOST/bll_api/gys_apply/get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758

# 供应商提交审核

    http -f $CY_GATE_HOST/bll_api/gys_apply/add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    name=康特小酒楼 \
    contract_name=张三 \
    contract_phone=13809522353 \
    addr=福建省福州市xx路 \
    gys_code=3545465454545 \
    gys_license="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2163440855,811056806&fm=27&gp=0.jpg" \
    shop_img="https://ss0.baidu.com/73t1bjeh1BF3odCf/it/u=3618234086,1199764508&fm=85&s=723059CBC636059E8315E91A03008093" \
    lat=64.1212312 \
    lng=119.1645646 \
    food_license="https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=4111742341,4038756735&fm=27&gp=0.jpg"


# 商品列表

    http -f $CY_GATE_HOST/bll_api/gys/goods_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    page=1 \
    page_size=10 \
    search="s.status=-1"

# 商品详情
    http -f $CY_GATE_HOST/bll_api/gys/goods_get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    goods_id=1


# 删除
    http -f $CY_GATE_HOST/bll_api/gys/goods_del \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    goods_id=1


# 添加

    http -f $CY_GATE_HOST/bll_api/gys/goods_add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    goods_name=黄瓜 \
    price=1 \
    cover="https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050" \
    imgs="https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050,https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050,https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050" \
    intro=简介 \
    content=详细介绍 \
    cate_id=1


# 修改

    http -f $CY_GATE_HOST/bll_api/gys/goods_reset \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    goods_id=25 \
    goods_name=黄瓜11 \
    price=11 \
    cover="https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050" \
    imgs="https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050,https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050,https://ss0.baidu.com/73x1bjeh1BF3odCf/it/u=2033816434,820626626&fm=85&s=D23E3CC4D6D9912E31101C790300C050" \
    intro=简介1 \
    content=详细介绍1 \
    cate_id=2

# 参与美食活动的活动的商家列表
    http -f $CY_GATE_HOST/bll_api/gys/join_atv_firm_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    search="s.name=*康*"

# 赠送列表
    http -f $CY_GATE_HOST/bll_api/gys/gift_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    page=1 \
    page_size=10 \
    search="s.status=0"
# 赠送详情
    http -f $CY_GATE_HOST/bll_api/gys/gift_get \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    gift_id=17

# 添加赠送
    http -f $CY_GATE_HOST/bll_api/gys/gift_add \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758 \
    firm_id=1 \
    gift_data='[
        {
            "goods_id":1,
           "num":1
        },
        {
            "goods_id":3,
            "num":3
        }
    ]'


# 分类列表
    http -f $CY_GATE_HOST/bll_api/gys/goods_cate_list \
    acc=m13809522353 \
    user_token=0597a19785acfd58b7de7dd532971758
