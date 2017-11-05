<?php
require "vendor/autoload.php";
//var_dump($_SERVER['REQUEST_URI']);//  /rest/index/index
app_init();

$arr=explode("/",$_SERVER['REQUEST_URI']);
$module=$arr[1]?:"rest";
$controller=$arr[2]?:"index";
$method=$arr[3]?:"index";
//echo $module;
//echo $controller;
//echo $method;

$obj=call_user_func_array(["modules\\{$module}\\controllers\\$controller","getIns"],[]);

call_user_func_array([$obj,$method],[]);
