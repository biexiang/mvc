<?php

class Model{

    protected $db = null;

    /**
     * Model constructor. DB直接在构造函数中加载进来
     */
    public function __construct()
    {
        $this->db = $this->load("db",true);
        $this->db->init(App::$_config['default']['db']['mysql']);
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