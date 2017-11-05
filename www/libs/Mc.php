<?php

class Mc {
    public static function get($key) {
        $memcache=new Memcache();
        $memcache->connect(MEMCACHE_IP, MEMCACHE_PORT);
//        $memcache = memcache_connect(MEMCACHE_IP, MEMCACHE_PORT);
        $v = $memcache->get($key);
        $memcache->close();
        return $v;
    }

    public static function getr($key, $expires, $MEMCACHE_COMPRESSED = false) {
//        $memcache = memcache_connect(MEMCACHE_IP, MEMCACHE_PORT);
        $memcache=new Memcache();
        $memcache->connect(MEMCACHE_IP, MEMCACHE_PORT);
        $v = $memcache->get($key);
        if ($v) {
            $memcache->set($key, $v, $MEMCACHE_COMPRESSED, $expires);
        }
        $memcache->close();
        return $v;
    }

    public static function set($key, $value, $MEMCACHE_COMPRESSED = false, $expires = 0) {
//        $memcache = memcache_connect(MEMCACHE_IP, MEMCACHE_PORT);
        $memcache=new Memcache();
        $memcache->connect(MEMCACHE_IP, MEMCACHE_PORT);
        if ($expires == 0)
            $memcache->set($key, $value, $MEMCACHE_COMPRESSED);
        else
            $memcache->set($key, $value, $MEMCACHE_COMPRESSED, $expires);
        $memcache->close();
    }

    public static function del($key) {
//        @$memcache = memcache_connect(MEMCACHE_IP, MEMCACHE_PORT);
        $memcache=new Memcache();
        $memcache->connect(MEMCACHE_IP, MEMCACHE_PORT);
        @$memcache->delete($key);
        @$memcache->close();
    }
}