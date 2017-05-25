<?php       //用PHP批量生成图片缩略图

function mkdirs($dirname,$mode=0777)    //创建目录(目录, ［模式］)
{
  if(!is_dir($dirname))
  {
    mkdirs($dirname,$mode); //如果目录不存在,递归建立
    return mkdir($dirname,$mode);
  }
  return true;
}

function savefile($filename,$content='')        //保存文件(文件, ［内容］)
{
  if(function_exists('file_put_contents'))
  {
    file_put_contents($filename,$content);
  }
  else
  {
    $fp=fopen($filename,"wb");
    fwrite($fp,$content);
    fclose($fp);
  }
}

function getsuffix($filename)       //获取文件名后缀
{
  return end(explode(".",$filename));
}

function checksuffix($filename,$arr)        //是否为允许类型(当前, 允许)
{
  if(!is_array($arr))
  {
    $arr=explode(",",str_replace(" ","",$arr));
  }
  return in_array(getsuffix($filename),$arr) ? 1 : 0;
}

class image
{
  var $src;           //源地址
  var $newsrc;        //新图路径(本地化后)
  var $allowtype=array(".gif",".jpg",".png",".jpeg");     //允许的图片类型
  var $regif=0;       //是否缩略GIF, 为0不处理
  var $keep=0;        //是否保留源文件(1为保留, 0为MD5)
  var $over=0;        //是否可以覆盖已存在的图片,为0则不可覆盖
  var $dir;           //图片源目录
  var $newdir;        //处理后的目录

  function __construct($olddir=null,$newdir=null)
  {
      //图片源目录   其实也就网络图片的时候用，本地图片无用
    $this->dir=$olddir ? $olddir : "./images/temp";
    $this->newdir=$newdir ? $newdir : "./images/s";
  }

  function reNames($src)
  {
    $md5file=substr(md5($src),10,10).strrchr($src,"."); //MD5文件名后(例如:3293okoe.gif)
    $md5file=$this->w."_".$this->h."_".$md5file;        //处理后文件名
    return $this->newdir."/".$md5file;                  //将源图片,MD5文件名后保存到新的目录里
  }

  function Mini($src,$w,$h,$q=80)     //生成缩略图 Mini(图片地址, 宽度, 高度, 质量)
  {
    $this->src=$src;
    $this->w=$w;
    $this->h=$h;
    if(strrchr($src,".")==".gif" && $this->regif==0)    //是否处理GIF图
    {
      return $this->src;
    }
    if($this->keep==0)      //是否保留源文件，默认不保留
    {
      $newsrc=$this->reNames($src);   //改名后的文件地址
    }
    else                    //保持原名
    {
      $src=str_replace("\\","/",$src);
      $newsrc=$this->newdir.strrchr($src,"/");
    }
    if(file_exists($newsrc) && $this->over==0)      //如果已存在,直接返回地址
    {
      return $newsrc;
    }
    if(strstr($src,"http://") && !strstr($src,$_SERVER['HTTP_HOST']))//如果是网络文件,先保存
    {
      $src=$this->getimg($src);
    }
    $arr=getimagesize($src);    //获取图片属性
    $width=$arr[0];
    $height=$arr[1];
    $type=$arr[2];
    switch($type)
    {
      case 1:     //1 = GIF，
        $im=imagecreatefromgif($src);
        break;
      case 2:     //2 = JPG
        $im=imagecreatefromjpeg($src);
        break;
      case 3:     //3 = PNG
        $im=imagecreatefrompng($src);
        break;
      default:
        return 0;
    }

    //处理缩略图
    $nim=imagecreatetruecolor($w,$h);
    $k1=round($h/$w,2);
    $k2=round($height/$width,2);
    if($k1<$k2)
    {
      $width_a=$width;
      $height_a=round($width*$k1);
      $sw=0;
      $sh=($height-$height_a)/2;

    }
    else
    {
      $width_a=$height/$k1;
      $height_a=$height;
      $sw=($width-$width_a)/2;
      $sh = 0;
    }

    //生成图片
    if(function_exists('imagecopyresampled'))
    {
      imagecopyresampled($nim,$im,0,0,$sw,$sh,$w,$h,$width_a,$height_a);
    }
    else
    {
      imagecopyresized($nim,$im,0,0,$sw,$sh,$w,$h,$width_a,$height_a);
    }
    if(!is_dir($this->newdir))
    {
      mkdir($this->newdir);
    }

    switch($type)       //保存图片
    {
      case 1:
        $rs=imagegif($nim,$newsrc);
        break;
      case 2:
        $rs=imagejpeg($nim,$newsrc,$q);
        break;
      case 3:
        $rs=imagepng($nim,$newsrc);
        break;
      default:
        return 0;
    }
    return $newsrc;     //返回处理后路径
  }

  function getimg($filename)
  {
    $md5file=$this->dir."/".substr(md5($filename),10,10).strrchr($filename,".");
    if(file_exists($md5file))
    {
      return $md5file;
    }
    //开始获取文件,并返回新路径
    $img=file_get_contents($filename);
    if($img)
    {
      if(!is_dir($this->dir))
      {
        mkdir($this->dir);
      }
      savefile($md5file,$img);
      return $md5file;
    }
  }

  function reImg($src,$w,$h,$q)   //转换缩略图(文件名和结构不变)
  {
    $this->keep=1;
    return $this->Mini($src,$w,$h,$q);      //return 生成的地址
  }

}

$image=new image();
echo $image->reImg("http://img05.tooopen.com/images/20150531/tooopen_sy_127457023651.jpg",75,75,80);
echo "<br/>";

?>