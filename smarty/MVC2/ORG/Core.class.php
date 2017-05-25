<?php
/**
 * Created by Wenju Yang.
 * 
 */
class Core extends Smarty{

    public function __construct(){
        parent::__construct();
        $this->setTemplateDir(ROOT.'/View');
        $this->setCompileDir(ROOT.'/View_c');
        $this->config_dir=ROOT.'/configs';
    }
}