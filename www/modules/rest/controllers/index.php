<?php
namespace modules\rest\controllers;
class index {
    public static function getIns() {
        return new index();
    }

    function index() {
        echo "1";
    }

}