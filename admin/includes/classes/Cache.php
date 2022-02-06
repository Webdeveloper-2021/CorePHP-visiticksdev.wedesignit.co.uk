<?php
namespace includes\classes;

class Cache
{
    private static $dir = TMP_FOLDER_PATH;

    /**
     * @param $key
     * @param $val
     */
    static function set($key, $val) {
        $val = var_export($val, true);
        // HHVM fails at __set_state, so just use object cast for now
        $val = str_replace('stdClass::__set_state', '(object)', $val);
        // Write to temp file first to ensure atomicity
        $tmp = self::$dir . $key . uniqid('', true) . '.tmp';
        file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
        rename($tmp, self::$dir . $key);
    }

    /**
     * @param $key
     * @return false|mixed
     */
    static function get($key) {
        @include self::$dir . $key;
        return isset($val) ? $val : false;
    }

    /**
     * @param $key
     */
    static function delete($key) {
        @unlink(self::$dir . $key);
    }
}