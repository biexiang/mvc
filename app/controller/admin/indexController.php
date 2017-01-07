<?php

class IndexController extends Controller {
    public function __construct()
    {
        parent::__construct();
        MYECHO("Test " . __CLASS__);
    }

    public function test(){
        MYECHO("hhahha");
    }


}




?>