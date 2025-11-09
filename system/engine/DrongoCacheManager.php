<?php

/**
 * Created by PhpStorm.
 * User: Sajid
 * Date: 12-11-15
 * Time: 20.15
 */
class DrongoCacheManager {

    public function getCache($type, $cacheId) {
        try {
            $path = DIR_CACHE.$type."/".$cacheId;
            if(!file_exists ($path)) {
                return null;
            }
            $handle = fopen($path, 'r');
            if($handle == false) {
                return null;
            }
            flock($handle, LOCK_SH);
            $data = fread($handle, filesize($path));
            flock($handle, LOCK_UN);
            fclose($handle);
            return $data;
        } catch(Exception $ex) {
            return null;
        }
    }

    public  function setCache($type, $cacheId, $cache) {
        try {
            $path = DIR_CACHE.$type."/".$cacheId;
            $file = fopen($path, "w");
            flock($file, LOCK_EX);
            fwrite($file, $cache);
            fflush($file);
            flock($file, LOCK_UN);
            fclose($file);
        } catch(Exception $ex) {}

    }

    public function clearCache($type, $cacheId) {

        try {
            $path = DIR_CACHE.$type."/".$cacheId;
            if(file_exists($path)) {
                unlink($path);
            }
        } catch(Exception $ex) {}

    }
}