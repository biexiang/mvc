<?php

spl_autoload_register(array("Autoload","main"));

class Autoload{
    public static function main($class){
        if(file_exists(APP_MODEL_PATH . $class . ".php")){
            require APP_MODEL_PATH . $class . ".php";
            return;
        }

        if(file_exists(APP_ENTITY_PATH . $class . ".php")){
            require APP_ENTITY_PATH . $class . ".php";
            return;
        }

        //如果还有其他目录的类需要加载 可以在注册一个自动加载器

    }
}






?>