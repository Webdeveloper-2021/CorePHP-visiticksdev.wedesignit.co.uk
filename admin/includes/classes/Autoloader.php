<?php
/**
 * Simple autoloader, so we don't need Composer just for this.
 */
class Autoloader
{
    public static function register($install_path)
    {
        spl_autoload_register(function ($class) use ($install_path) {
            $file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $install_path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}
Autoloader::register($install_path);