<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    //导入
    public function eximport(){   
    	$upload = new \Think\Upload();
    	$upload->maxSize   =     3145728 ;    
    	$upload->exts      =     array('xls', 'csv', 'xlsx');  
    	$upload->rootPath  =      './Public';    
    	$upload->savePath  =      '/excel/';    
    	$info   =   $upload->upload();
    	if(!$info){
    		$this->error($upload->getError());
    	}else{
    		$filename='./Public'.$info['excel']['savepath'].$info['excel']['savename'];
    		import("Org.Yufan.ExcelReader");
    		$ExcelReader=new \ExcelReader();
    		$arr=$ExcelReader->reader_excel($filename);
    		foreach ($arr as $key => $value) {
    			$data['username']=$arr[$key]['2'];
    			$data['money']=$arr[$key]['3'];
    			$data['time']=strtotime($arr[$key]['4']); 
    			M('dc')->add($data);   			
    		}
    		$this->success('导入成功');
    	}
    }

    //导出
    public function export(){
    	import("ORG.Yufan.Excel");
    	$list = M('dc')->select();
    	$row=array();
    	$row[0]=array('序号','用户id','用户名','金额','时间');
    	$i=1;
    	foreach($list as $v){
    	        $row[$i]['i'] = $i;
    	        $row[$i]['uid'] = $v['id'];
    	        $row[$i]['username'] = $v['username'];
    	        $row[$i]['money'] = $v['money'];
    	        $row[$i]['time'] = date("Y-m-d H:i:s",$v['time']);
    	        $i++;
    	}
    	
    	$xls = new \Excel_XML('UTF-8', false, 'datalist');
    	$xls->addArray($row);
    	$xls->generateXML("yufan956932910");
    }


}