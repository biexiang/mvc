<?php

final class Route{

    private $urlType = 1;//默认为1
    private $path;
    private $info = array();

    public function __construct()
    {
        //也可以parse_url进行分析
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function setUrlType($type){
        $this->urlType = $type;
    }

    public function route(){
        $this->getUrlInfo();
        return $this->info;
    }

    public function getUrlInfo(){
        switch ($this->urlType) {
            case 1 :
                $this->info['app'] = isset($_GET['app']) ? $_GET['app'] : "";
                $this->info['controller'] = isset($_GET['c']) ? $_GET['c'] : "Home";
                $this->info['action'] = isset($_GET['a']) ? $_GET['a'] : "index";
                unset($_GET['app']);
                unset($_GET['c']);
                unset($_GET['a']);
                if (!empty($_GET)) {
                    $this->info['params'] = $_GET;
                }
                break;
            case 2 :
                //pathinfo路由 http://m.com/admin/index/index/id/2
                //先假设没有分级目录
                $tmp = explode("/",$this->path);
                $tmp = array_filter($tmp);
                $tmp = array_merge($tmp,array());
                if($tmp[0] == "index.php"){
                    array_shift($tmp);
                }

                $this->info['controller'] = $tmp[0];
                array_shift($tmp);
                $this->info['action'] = $tmp[0];
                array_shift($tmp);
                if(!empty($tmp)){
                    //理论上 是键值对存在的
                    $i = 0;
                    while($i < sizeof($tmp)){
                        $key = $tmp[$i];
                        if(!isset($tmp[$i + 1])){
                            break;
                        }

                        $this->info['params'][$key] = $tmp[$i + 1];
                        $i = $i + 2;
                    }
                }
                break;
        }
    }

}



?>















