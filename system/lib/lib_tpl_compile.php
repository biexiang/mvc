<?php

class Compile{
    private $tpl;
    private $content;
    private $target;
    private $value = array();
    private $left = "{";
    private $right = "}";
    private $T_P = array();
    private $T_R = array();

    //需要优化定界符的配置

    public function __construct($template,$target)
    {
        $this->tpl = $template;
        $this->target = $target;

        $this->content = file_get_contents($template);

        /*变量规则*/
        $this->T_P[] = "#\{\s*\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#";
        $this->T_R[] = "<?php echo \$this->value['\\1']; ?>";

        /**
         * foreach|loop规则
        {foreach $b}<li>{v}</li>{/foreach}
         */
        $this->T_P[] = "#\{\s*foreach\s*\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}#i";
        $this->T_P[] = "#\{\s*\/foreach\s*\}#i";
        $this->T_P[] = "#\{\s*([k|v])\s*\}#";
        $this->T_R[] = "<?php foreach( (array)\$this->value['\\1'] as \$k => \$v ){ ?>";
        $this->T_R[] = "<?php } ?>";
        $this->T_R[] = "<?php echo \$\\1 ?>";

        /**
         * if判断
         */
        $this->T_P[] = "#\{\s*if (.* ?)\s*\}#i";
        $this->T_P[] = "#\{\s*(elseif|else if) (.* ?)\s*\}#";
        $this->T_P[] = "#\{\s*else\s*\}#";
        $this->T_P[] = "#\{\s*\/if\s*\}#";

        $this->T_R[] = "<?php  if( \\1 ){ ?>";
        $this->T_R[] = "<?php }elseif(\\2){ ?>";
        $this->T_R[] = "<?php }else{ ?>";
        $this->T_R[] = "<?php } ?>";

        /*去掉注释*/
        $this->T_P[] = "#\{(\#|\*)(.* ?)(\#|\*)\}#";
        $this->T_R[] = "";

    }

    public function compile(){
        $this->express();
        $this->resource();
        file_put_contents($this->target,$this->content);
    }

    public function express(){
        $this->content = preg_replace($this->T_P,$this->T_R,$this->content);
    }

    public function resource(){
        /*{! jquery.js !} and css... */
        $this->content = preg_replace("#\{\!\s* (.* ?)(\.js) \s*\!\}#",'<script src="\\1.js?t=' . time()  . '"></script>',$this->content);
        $this->content = preg_replace("#\{\!\s* (.* ?)(\.css) \s*\!\}#",'<link rel="stylesheet" href="\\1.css?t=' . time()  . '">',$this->content);
    }


    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

}


?>