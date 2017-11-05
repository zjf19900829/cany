# CY_GATE_HOST=http:/127.0.0.1:8789
# CY_GATE_HOST=http://canyun-gateway.serv.boois.cn


# 酒楼审核 status

    1通过 -1通过 -2 永久不通过

    http -f $CY_GATE_HOST/bll_api/firm_apply/approve \
    admin_key=admin \
    admin_sec=admin123123 \
    firm_apply_id=1 \
    status=-2 \
    reason=营业执照不清晰


# 供应商审核 status

    1通过 -1通过 -2 永久不通过

    http -f $CY_GATE_HOST/bll_api/gys_apply/approve \
    admin_key=admin \
    admin_sec=admin123123 \
    gys_apply_id=1 \
    status=1 \
    reason=营业执照不清晰
