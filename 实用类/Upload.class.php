<?php
class Upload{
    /*
     * 将上传的三维数组转为二维数组
     * @return array
     */
    function getFiles(){
        $i=0;
        foreach($_FILES as $files){
            //情况一
            if(is_string($files['name'])){
                $arr[$i]=$files;
                $i++;
            }elseif(is_array($files['name'])){
                //情况二(三维数组)
                foreach($files['name'] as $k=>$v){
                    $arr[$i]['name']=$files['name'][$k];
                    $arr[$i]['type']=$files['type'][$k];
                    $arr[$i]['tmp_name']=$files['tmp_name'][$k];
                    $arr[$i]['error']=$files['error'][$k];
                    $arr[$i]['size']=$files['size'][$k];
                    $i++;
                }
            }

        }
        return $arr;
    }

    /**
     * 文件上传
     * @param string $uploadDir 上传文件保存的目录名称
     * @param int $maxSize 允许上传文件的大小
     * @param array $allowExt 允许上传的文件类型
     * @return $arr;
     */

   function uploads($uploadDir,$maxSize=1048576,$allowExt=array('jpg','jpeg','png','gif'),$flag=true){
        $fileArr=$this->getFiles();
        foreach($fileArr as $k=>$files){
            $name=$files['name'];
            $tmp_name=$files['tmp_name'];
            $error=$files['error'];
            $size=$files['size'];

            if($error==UPLOAD_ERR_OK){
                //判断文件大小是否超过允许最大值
                if($size>$maxSize){
                    $res[$k]['msg']='上传的文件大小不能超过1M';
                    continue;
                }
                //判断文件类型是否允许
                $ext=pathinfo($name,PATHINFO_EXTENSION);
                if(!in_array($ext,$allowExt)){
                    $res[$k]['msg']='上传的文件类型不被允许';
                    continue;
                }
                //需要判断时判断文件是否是一个真实的图片
                if($flag && !getimagesize($tmp_name)){
                    $res[$k]['msg']='上传的文件类型不被允许';
                    continue;
                };
                //验证上传方式是否为http-post
                if(!is_uploaded_file($tmp_name)){
                    $res[$k]['msg']='文件提交方式不正确';
                    continue;
                }
                //获取唯一的文件名
                include_once "Common.class.php";
                $common=new Common();
                $filename= $common->uniqStr().'.'.$ext;
                //上传的目录不存在时自动创建
                if(!file_exists($uploadDir)){
                    mkdir($uploadDir);
                    chmod($uploadDir,0777);
                }
                $des=$uploadDir.'/'.$filename;
                //将文件从临时目录移动到指定目录
                if(move_uploaded_file($tmp_name,$des)){
                    $res[$k]['filename']=$filename;
                };
            }else{
                switch($error){
                    case 1:
                        $res[$k]['msg']='超过PHP配置文件upload_max_filesize所规定的字节数';
                        break;
                    case 2:
                        $res[$k]['msg']='上传文件字节数超过表单隐藏域(MAX_FILE_SIZE)规定的字节数';
                        break;
                    case 3:
                        $res[$k]['msg']='文件部分被上传';
                        break;
                    case 4:
                        $res[$k]['msg']='没有选择要上传的文件';
                        break;
                    case 6:
                        $res[$k]['msg']= '临时目录不存在';
                        break;
                    case 7:
                        $res[$k]['msg']='临时目录没有写入权限';
                        break;
                    default:
                        $res[$k]['msg']= "未知错误";
                }
            }
        }
        return $res;
    }
}