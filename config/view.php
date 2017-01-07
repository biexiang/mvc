<?php

$CONFIG['default']['tpl'] = array(
    "tpldir"=> APP_VIEW_PATH ,
    "suffix"=>".tpl",
    "cachedir"=> ROOT_PATH . "storage/views/" ,
    "need_cache"=> true,
    "suffix_static"=>".html",
    "suffix_dynamic"=>".php",
    "expire"=>10,
);


?>