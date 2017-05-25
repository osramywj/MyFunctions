<?php
class Common{

    /**
     * @param $params  ：要过滤的参数
     * @return mixed   ：过滤后的url
     */
    function filter_url($params)
    {
        // 获取当前地址的 URL
        $re = "/\/$params\/[^\/]*/";
        return preg_replace($re, '', $_SERVER['PHP_SELF']);
    }



    /**
     * 将一个字符串部分字符用$re替代隐藏
     * @param string    $string   待处理的字符串
     * @param int       $start    规定在字符串的何处开始，
     *                            正数 - 在字符串的指定位置开始
     *                            负数 - 在从字符串结尾的指定位置开始
     *                            0 - 在字符串中的第一个字符处开始
     * @param int       $length   可选。规定要隐藏的字符串长度。默认是直到字符串的结尾。
     *                            正数 - 从 start 参数所在的位置隐藏
     *                            负数 - 从字符串末端隐藏
     * @param string    $re       替代符
     * @return string   处理后的字符串
     */

    function hidestr($string, $start = 0, $length = 0, $re = '*') {
        if (empty($string)) return false;
        $strarr = array();
        $mb_strlen = mb_strlen($string);
        while ($mb_strlen) {
            $strarr[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $mb_strlen, 'utf8');
            $mb_strlen = mb_strlen($string);
        }
        $strlen = count($strarr);
        $begin  = $start >= 0 ? $start : ($strlen - abs($start));
        $end    = $last   = $strlen - 1;
        if ($length > 0) {
            $end  = $begin + $length - 1;
        } elseif ($length < 0) {
            $end -= abs($length);
        }
        for ($i=$begin; $i<=$end; $i++) {
            $strarr[$i] = $re;
        }
        if ($begin >= $end || $begin >= $last || $end > $last) return false;
        return implode('', $strarr);
    }

    /**
     * 冒泡排序
     * 比如：2,4,1    // 第一次 冒出的泡是4
     *      2,1,4   // 第二次 冒出的泡是 2
     *      1,2,4   // 最后就变成这样
     * @param $arr
     * @return mixed
     */

    function maopao($arr){
        $len=count($arr);
        //设置一个空数组用来接收冒出来的泡
        //该层控制循环 需要冒泡的轮数
        for($i=1;$i<$len;$i++){
            //该层用来控制每轮冒出一个数需要比较的次数
            for ($k=0;$k<$len-$i;$k++){
                if ($arr[$k]>$arr[$k+1]){
                    $tmp=$arr[$k+1];
                    $arr[$k+1]=$arr[$k];
                    $arr[$k]=$tmp;
                }
            }
        }
        return $arr;
    }

    /**
     * 快速排序
     * 将小于标尺的放到左侧数组、大于标尺的放入右侧数组，然后对两侧数组分别递归，最后合并即可
     * @param $arr
     * @return array
     */

    function quick_sort($arr){
        //判断是否需要排序
        $len=count($arr);
        if ($len<=1){
            return $arr;
        }
        $base_num=$arr[0];//选择一个标尺
        $left_arr=array();//小于标尺的
        $right_arr=array();//大于标尺的
        for ($i=1;$i<$len;$i++){
            if ($base_num>$arr[$i]){
                $left_arr[]=$arr[$i];
            }else{
                $right_arr[]=$arr[$i];
            }
        }

        //分别对左边右边递归调用这个函数，记录结果
        $left_arr=quick_sort($left_arr);
        $right_arr=quick_sort($right_arr);
        //合并左边、标尺、右边
        return array_merge($left_arr,array($base_num),$right_arr);
    }

    /**
     * 二维数组的排序
     * @param $arr  二维数组
     * @param $key  需要按哪个字段排序
     * @param string $type  升序还是降序
     * @return array
     */
    function arr_sort($arr,$key,$type='asc'){
        $tempArr=$newArr=array();
        foreach($arr as $k => $v){
            $tempArr[$k]=$v[$key];
        }

        if($type=='asc'){
            asort($tempArr);
        }elseif($type=='desc'){
            arsort($tempArr);
        }

        foreach($tempArr as $k => $v){
            $newArr[$k]=$arr[$k];
        }
        return $newArr;
    }

    /**
     * @param $img   //图像文件绝对路径
     * @return array  //关于图片宽、高、类型、大小的数组
     */
    function get_img_info($img){
        $img_info = getimagesize($img);
        switch ($img_info[2]) {
            case 1:
                $imgtype = "GIF";
                break;
            case 2:
                $imgtype = "JPG";
                break;
            case 3:
                $imgtype = "PNG";
                break;
        }
        $img_type = $imgtype . "图像";
        $img_size = ceil(filesize($img) / 1000) . "k"; //获取文件大小

        return $new_img_info = array(
            "width" => $img_info[0],
            "height" => $img_info[1],
            "type" => $img_type,
            "size" => $img_size
        );
    }



    /**
     * 生成唯一字符串，不重复
     * @return $string
     */

    function uniqStr(){
        return md5(uniqid(microtime(true)));
    }

    /**
     * 生成指定长度的随机字符串
     * @param $type 字符串类型 1代表纯数字 2代表大写字母 3代表小写字母 4代表大小字母组合 5代表数字和大小写字母的组合
     * @param $length 字符串的长度
     * @return $string
     */


    function getRandString($type=1,$length=4){
        switch($type){
            case 1:
                $str=join('',range(0,9));
                break;
            case 2:
                $str=join('',range('a','z'));
                break;
            case 3:
                $str=join('',range('A','Z'));
                break;
            case 4:
                $str=join('',array_merge(range('a','z'),range('A','Z')));
                break;
            case 5:
                $str=join('',array_merge(range(0,9),range('a','z'),range('A','Z')));
                break;
        }
        $str=str_shuffle($str);
        return substr($str,0,$length);
    }

    function success($info){
        $res['status']=1;
        $res['info']=$info;
        echo json_encode($res);
    }


    function error($info){
        $res['status']=0;
        $res['info']=$info;
        echo json_encode($res);
    }
}