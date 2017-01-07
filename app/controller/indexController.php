<?php



class IndexController extends Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function test($args){
        print_r($args);
    }

    public function tpl(){
        $data = array(
            "a"=>"完成加载自己的简单视图解析引擎",
            "b"=>"没有定义系统的Controller和model的功能",
            "c"=>"还学要写pathinfo路由",
            "d"=>"PDO ORM操作MYSQL"
        );

        $this->showTpl("admin/index",$data);
        $this->showTpl("admin/footer",array("q"=>"don't be evil"));
    }

    public function usrlib(){
        $hello = parent::load("hello",false);
        $hello->test();
    }

    public function model(){
        include APP_MODEL_PATH . "testModel.php";
        $m = new testModel();
        $m->test();
    }


}


?>