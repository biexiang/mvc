<?php

/*spl_autoload_register('autoload_usr');

function autoload_usr($classname){
    $arr = explode("\\",$classname);
    print_r($arr);
    if($arr[0] == "app"){
        if($arr[1] == "model"){
            MYECHO(APP_MODEL_PATH . $arr[2] . ".php");
            require APP_MODEL_PATH . $arr[2] . ".php";
        }
    }
}*/



spl_autoload_register('autoload_usr_entity');
spl_autoload_register('autoload_usr_model');

//如果还有其他目录的类需要加载 可以在注册一个自动加载器

function autoload_usr_entity($classname){
    MYECHO(APP_ENTITY_PATH);
    if(file_exists(APP_ENTITY_PATH . $classname . ".php")){
        require APP_ENTITY_PATH . $classname . ".php";
    }else{
        autoload_usr_model($classname);
    }
}

/**
 * @param $classname 需要自动加载的类名
 */
function autoload_usr_model($classname){
    require APP_MODEL_PATH . $classname . ".php";
}






?>