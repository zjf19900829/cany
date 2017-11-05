CY_GATE_HOST=http:/127.0.0.1:8789
CY_GATE_HOST=http://canyun-gateway.serv.boois.cn



curl $CY_GATE_HOST/bll_api/attach/upload -F "file=@README.txt"

# debug
curl http://canyun-bll.serv.boois.cn/rest/attach/upload -F "file=@README.txt"