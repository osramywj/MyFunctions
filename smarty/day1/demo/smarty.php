<?php
class Smarty{
    private $arr=array();
    public function assign($name,$value){
        $this->arr[$name]=$value;
    }
    //display($tplname)括号里的$tplname是单纯的模板文件名，不含路径的
    public function display($tplname){
        $tpl="./templates/".$tplname;
        $templates_c="./templates_c/".$tplname."_compile.php";

        if(!file_exists($templates_c) ||filemtime($templates_c)<filemtime($tpl)){
            $tplfile=file_get_contents($tpl);
            //'$'符号在正则表达式中表示以XXX结尾，有特殊含义，所以需要转义；
            $reg='/{\$([a-zA-Z_]\w*)}/';
            $replace="<?php echo \$this->arr['\\1']?>";
            $tpl_cfile=preg_replace($reg,$replace,$tplfile);
            file_put_contents($templates_c,$tpl_cfile);
        }
        include_once "$templates_c";
    }

}