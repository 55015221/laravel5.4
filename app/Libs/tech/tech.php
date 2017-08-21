<?php
/**
 * E签宝接口入口
 * @author 郭正
 * @date 2017-6-27
 */

namespace App\Libs\tech;


use tech\core\eSign;

class tech
{
    /* @var $_instance eSign */
    private static $_instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * 单例
     * @param array $config
     * @return eSign
     */
    static public function getInstance($config = [])
    {
        try {
            if(empty($config)){
                $config = config('esign');
            }
            if (is_null(self::$_instance) || isset(self::$_instance)) {
                self::$_instance = new eSign($config);
            }
            return self::$_instance;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

}
/*
//sdk类文件自动加载
spl_autoload_register(function ($class) {
    $class_path = str_replace('tech\\', '', $class);
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_path);
    $class_file = ESIGN_ROOT . DIRECTORY_SEPARATOR . $class_path . '.php';
    //echo $class_file;
    if (is_file($class_file)) {
        require_once($class_file);
    }
});*/
