<?php

define("SYS_PATH",dirname(__FILE__) . "/");
define("SYS_CORE_PATH",SYS_PATH . "core/");
define("SYS_LIB_PATH",SYS_PATH . "lib/");
define("ROOT_PATH",substr(SYS_PATH,0,-7));
define("APP_PATH",ROOT_PATH . "app/");
define("APP_CTRL_PATH",APP_PATH . "controller/");
define("APP_MODEL_PATH",APP_PATH . "model/");
define("APP_VIEW_PATH",APP_PATH . "view/");
define("APP_LIB_PATH",APP_PATH . "lib/");


final class APP{
    public static $_lib = array();
    public static $_config = array();

    public static function run($config){
        self::$_config = $config;
        self::init();
    }

    public static function init(){
        /*define and set*/
        require SYS_CORE_PATH . "Controller.php";
        require SYS_CORE_PATH . "Model.php";
        self::setLibs();
        self::autoLoad();

        /*开始分析路由信息 并且调用控制器和模型返回视图*/
        self::$_lib['route']->setUrlType(self::$_config['default']['url_type']);
        $info = self::$_lib['route']->route();
        self::toIns($info);
    }

    public static function autoLoad(){
        foreach(self::$_lib as $k => $v){
            require $v;
            $class = ucfirst($k);
            self::$_lib[$k] = new $class;
        }
    }

    public static function setLibs(){
        self::$_lib = array(
            "route" => SYS_LIB_PATH . "lib_route.php",
            "db" => SYS_LIB_PATH . "lib_db.php",
            "tpl"=> SYS_LIB_PATH . "lib_tpl.php",
        );
    }

    public static function toIns($arrInfo){
        //文件名小写 类首字母大写
        //如何自动加载类？
        $ctrlPath = empty($arrInfo['app']) ? APP_CTRL_PATH . $arrInfo['controller'] . "Controller.php" : APP_CTRL_PATH . $arrInfo['app'] . "/" . $arrInfo['controller'] . "Controller.php";
        if(!file_exists($ctrlPath)){
            exit("控制器不存在");
        }
        require $ctrlPath;
        $class = ucfirst($arrInfo['controller']) . "Controller";
        $ins = new $class;
        $action = $arrInfo['action'];
        if(!method_exists($ins,$action)){
            exit("没有这个方法");
        }
        isset($arrInfo['params']) ? $ins->$action($arrInfo['params']) : $ins->$action();

    }

    public static function newLib($lib){
        //加载用户自定义lib
        //路径 => require => new => push
        //文件名格式 默认 lib_<libname>.php
        $usr_lib_path = APP_LIB_PATH . "lib_" . $lib . ".php";
        if(!file_exists($usr_lib_path)){
            exit("请求的用户类不存在");
        }
        require $usr_lib_path;
        $class = ucfirst($lib);
        $ins = new $class;
        //self::$_lib[$lib] = $ins;
        return $ins;
    }


}










?>