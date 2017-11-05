#!/usr/bin/env bash
cp -a API_ADMIN.md /d01/projects/docs/c餐云网/管理后台/
cp -a API_ATTACH.md /d01/projects/docs/c餐云网/公共/
cp -a API_FIRM.md /d01/projects/docs/c餐云网/商家端/
cp -a API_GYS.md /d01/projects/docs/c餐云网/供应商端/
cp -a API_USER.md /d01/projects/docs/c餐云网/用户端/

cd /d01/projects/docs
git add .
git commit -a -m -
git push