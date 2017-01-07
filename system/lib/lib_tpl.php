<?php

class Tpl{
    /**
     * 默认配置
     */
    private $arrayConfig = array(
        "tpldir"=> APP_VIEW_PATH ,
        "suffix"=>".tpl",
        "cachedir"=> ROOT_PATH . "storage/views/" ,
        "need_cache"=> true,
        "suffix_static"=>".html",
        "suffix_dynamic"=>".php",
        "expire"=>1800,
    );

    static private $instance;
    private $file;
    private $compileTool;
    private $value = array();

    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct($config = array())
    {
        /*数组相加 前面存在的后面不可覆盖 使用前面的*/
        $this->arrayConfig = $config + $this->arrayConfig;

        $this->makePath();
        if(!is_dir($this->arrayConfig['tpldir'])){
            exit("Err,template dir not exists");
        }
        if(!is_dir($this->arrayConfig['cachedir'])){
            mkdir($this->arrayConfig['cachedir'],0770,true);
        }

        include_once "lib_tpl_compile.php";

    }

    /**
     * @param $k 键或者数组
     * @param null $v 对应键的值
     */
    public function setConfig($k,$v = null){
        if(is_array($k)){
            $this->arrayConfig = $k + $this->arrayConfig;
        }else{
            $this->arrayConfig[$k] = $v;
        }
    }


    /**
     * @param null $k
     * @return array|mixed
     */
    public function getConfig($k = null){
        if($k){
            return $this->arrayConfig[$k];
        }else{
            return $this->arrayConfig;
        }
    }

    /**
     * @param $k
     * @param $v
     */
    public function assign($k,$v){
        $this->value[$k] = $v;
    }

    /**
     * @param $array
     */
    public function assignArray($array){
        foreach ($array as $k => $v){
            $this->value[$k] = $v;
        }
    }



    public function makePath(){
        $this->arrayConfig['tpldir'] = strtr(realpath($this->arrayConfig['tpldir']),'\\','/') . '/';
        $this->arrayConfig['cachedir'] = strtr(realpath($this->arrayConfig['cachedir']),'\\','/') . '/';
    }

    public function path(){
        return $this->arrayConfig['tpldir'] . $this->file . $this->arrayConfig['suffix'];
    }

    /**
     * 判断是否重新生成static文件
     */
    public function recache($name){
        $staticfile = $this->arrayConfig['cachedir'] . md5($name) . $this->arrayConfig['suffix_static'];

        if($this->arrayConfig['need_cache'] == true){
            if(!is_file($staticfile)){
                return true;
            }

            $is_expire = (time() - filemtime($staticfile)) < $this->arrayConfig['expire']? false:true;

            if($is_expire){
                return true;
            }
            return false; //没有过期

        }else{
            return false; //不需要生成static文件
        }


    }

    public function clear($file = null){
        if(is_null($file)){
            $todo = glob($this->arrayConfig['cachedir'] . "*" . $this->arrayConfig['suffix_static']);
        }else{
            $todo = $this->arrayConfig['cachedir'] . md5($file) . $this->arrayConfig['suffix_static'];
        }
        foreach ((array)$todo as $v){
            unlink($v);
        }

    }



    public function show($name){
        $this->file = $name;
        if(!is_file($this->path())){
            exit($name . " template not exists");
        }

        $originfile = $this->path();
        $dynamicfile = $this->arrayConfig['cachedir'] . md5($name) . $this->arrayConfig['suffix_dynamic'];
        $staticfile = $this->arrayConfig['cachedir'] . md5($name) . $this->arrayConfig['suffix_static'];

        if($this->recache($name) == true){
            /*需要正则解析 并且把value 赋值进来*/
            ob_start();
            extract($this->value,EXTR_OVERWRITE);
            $this->compileTool = new Compile($originfile,$staticfile);
            $this->compileTool->value = $this->value;
            $this->compileTool->compile();
            include $staticfile;
            $tmp = ob_get_contents();
            file_put_contents($staticfile,$tmp);
            //echo "生成文件:" . $staticfile;
        }else{
            if($this->arrayConfig['need_cache'] == false){
                $this->compileTool = new Compile($originfile,$dynamicfile);
                $this->compileTool->compile();
                readfile($dynamicfile);
                echo "生成文件:" . $dynamicfile;
            }else{
                readfile($staticfile);
                //echo $staticfile . "还没过期";
            }
        }

    }
}


?>