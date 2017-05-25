<?php

//redis生成页面缓存类
class redisCache {
  /**
   * $host : redis服务器ip
   * $port : redis服务器端口
   * $lifetime : 缓存文件有效期,单位为秒
   * $cachePath : 缓存文件路径,包含文件名
   */
  private $host;
  private $port;
  private $lifetime;
  private $cachePath;
  private $data;
  public $redis;
  private $dir;//缓存的目录
  /**
   * 构造函数,检查缓存目录是否有效,默认赋值
   */
  function __construct($dir,$lifetime=1800) {
    //配置
    $this->dir = $dir;
    $this->host = "127.0.0.1";
    $this->port = "6379";
    $redis = new Redis();
    $redis->pconnect($this->host,$this->port);
    $this->redis=$redis;
    $this->cachePath = $this->getcachePath();
    $this->lifetime = $lifetime;
    $this->data=$redis->hMGet($this->cachePath, array('content','creattime'));
    //print_r($this->redis);
    //print_r($this->data);
  }


  /**
   * 检查缓存是否有效
   */
  private function isvalid(){
    $data=$this->data;
    if (!$data['content']) return false;
    if (time() - $data['creattime'] > $this->lifetime) return false;
    return true;
  }
  /**
   * 写入缓存
   * $mode == 0 , 以浏览器缓存的方式取得页面内容
   */
  public function write($mode=0,$content='') {
    switch ($mode) {
      case 0:
        $content = ob_get_contents();
        break;
      default:
        break;
    }
    ob_end_flush();
    try {
      $this->redis->hMset($this->cachePath, array('content'=>$content,'creattime'=>time()));
      $this->redis->expireAt($this->cachePath, time() + $this->lifetime);
    }
    catch (Exception $e) {
      $this->error('写入缓存失败!');
    }
  }
  /**
   * 加载缓存
   * exit() 载入缓存后终止原页面程序的执行,缓存无效则运行原页面程序生成缓存
   * ob_start() 开启浏览器缓存用于在页面结尾处取得页面内容
   */
  public function load() {
    if ($this->isvalid()) {
      echo $this->data['content'];
      exit();
    }
    else {
      ob_start();
    }
  }
  /**
   * 清除缓存
   */
  public function clean() {
    try {
      $this->redis->hDel($this->cachePath, array('content','creattime'));
    }
    catch (Exception $e) {
      $this->error('清除缓存失败!');
    }
  }
  /**
   * 取得缓存文件路径
   */
  private function getcachePath() {
    return $this->dir.md5($this->geturl()).'.php';
  }
  /**
   * 取得当前页面完整url
   */
  private function geturl() {
    $url = '';
    if (isset($_SERVER['REQUEST_URI'])) {
      $url = $_SERVER['REQUEST_URI'];
    }
    else {
      $url = $_SERVER['PHP_SELF'];
      $url .= empty($_SERVER['QUERY_STRING'])?'':'?'.$_SERVER['QUERY_STRING'];
    }
    return $url;
  }
  /**
   * 输出错误信息
   */
  private function error($str) {
    echo '<div style="color:red;">'.$str.'</div>';
  }
}


//用法：
// require_once('redisCache.php');
// $cache = new redisCache(10); //设置缓存生存期
// if ($_GET['clearCache']) $cache->clean();
// else  $cache->load(); //装载缓存,缓存有效则不执行以下页面代码


// //页面代码开始

// //页面代码结束
// $cache->write(); //首次运行或缓存过期,生成缓存

?>