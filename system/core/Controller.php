<?php

class Controller{
    public function __construct()
    {
        //MYECHO("I am your father" . __CLASS__);
    }

    /**
     * @param $view  视图名称及路径
     * @param $data  传到视图的数据
     */
    final protected function showTpl($view,$data = null){
        //admin/index
        $tpl = $this->load("tpl",true);
        $tpl->setConfig(App::$_config['default']['tpl']);
        if(strpos($view,"/")){
            $tmp = substr($view,0,strripos($view,"/"));
            $tpl->setConfig("tpldir",APP_VIEW_PATH . $tmp . "/");
        }
        if(!is_null($data)){
            $tpl->assignArray($data);
        }
        $tpl->show(substr(strrchr($view,"/"),1));
    }


    /**
     * @param $lib 类名
     * @param bool $is_syslib 是否加载系统类
     * @return mixed  返回实例化的对象
     */
    final protected function load($lib,$is_syslib = true){
        if(empty($lib)){
            exit("加载模型类不能为空");
        }

        if($is_syslib){
            if(!array_key_exists($lib,App::$_lib)){
                exit("系统模型类中不存在请求的类");
            }
            return App::$_lib[$lib];
        }else{
            //加载用户模型类
            return App::newLib($lib);
        }
    }


}




?>