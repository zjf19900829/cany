<?php

class Mdb {
    private static $ins;
    private $db;


    public function __construct($dbname, $serv, $user, $psw, $port) {
        $this->db = new \Medoo\Medoo(array(
            'database_type' => 'mysql',
            'database_name' => $dbname,
            'server' => $serv,
            'username' => $user,
            'password' => $psw,
            'charset' => 'utf8',
            'port' => $port,
        ));
        Mdb::$ins = $this;
    }

    public static function getIns() {
        if (!self::$ins) {
            exit("mdb_not_init");
        }
        return self::$ins;
    }

    public function isOk() {
        $tmp = $this->db->error();
        return $tmp[1] == null;
    }

    public function error() {
        $tmp = $this->db->error();
        return implode(",",$tmp);
    }

    public function __call($name, $arguments) {
        $ret = call_user_func_array(array($this->db, $name), $arguments);
        if (!$this->isOk()) {
            $error = $this->db->error();
            BooisLog::ossLog("mysql_err", "sql: " . json_encode($this->db->log(), JSON_UNESCAPED_UNICODE) . " err: " . $error[2] . " trace: " . json_encode(debug_backtrace()));
        }
        return $ret;

    }


}