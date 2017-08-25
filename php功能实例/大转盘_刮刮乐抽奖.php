<?php
/**
 * 大转盘、刮刮乐等抽奖，抽中之后奖品从奖池删除，剩余奖品的中奖概率等于总概率减去已抽奖品概率；
 */

//定义一个奖品数组，id为几等奖，prize为奖品名称，v为中奖概率，
//下面例子中sun(v)=100,所以平板电脑的中奖概率就是1%;
$prize_arr = array(
    '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1,'num'=>1),
    '1' => array('id'=>2,'prize'=>'数码相机','v'=>5,'num'=>2),
    '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10,'num'=>5),
    '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12,'num'=>10),
    '4' => array('id'=>5,'prize'=>'10Q币','v'=>22,'num'=>20),
    '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
);


//将奖品的数组放入redis中；
$redis = new Redis();
$redis->connect('192.168.1.202',6379);
$redis->auth('youderedis202');
$redis->lPush('prize',json_encode($prize_arr,true));

$arr = $redis->lRange('prize',0,-1);


/**
 * 从$prize_arr中随机出来一条记录；
 * 因为没中奖也在$prize_arr之中，所以函数每次总会返回一个结果的，要么中奖，要么就是id=>6
 * @param $proArr  :中奖概率的数组
 * array(
      1=>1,
      2=>5,
      3=>10,
      4=>12,
      5=>22,
      6=>50
   )
 * @return int|string   中了几等奖
 */
function get_rand($prize_arr) {
    //$proArr  :中奖概率的数组
    /*形如array(
            1=>1,
            2=>5,
            3=>10,
            4=>12,
            5=>22,
            6=>50
    )*/
    foreach ($prize_arr as $key => $val) {
        $proArr[$val['id']] = $val['v'];
    }

    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    //例：第一个循环是1=>1,若$randNum是10，大于1，$proSum减去1，继续循环。
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);//在1~100随机一个数字
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}

/**
 * 抽奖的函数，每次调用，中奖后奖池内的奖品就会从redis里删除；
 * @param $prize_arr :奖品数组
 * @return mixed
 */
function lotto()
{
    $redis = new Redis();
    $redis->connect('192.168.1.202',6379);
    $redis->auth('youderedis202');
    $prize_arr = $redis->lGet('prize',0);
    $prize_arr = json_decode($prize_arr,true);

    $rid = get_rand($prize_arr); //根据概率获取奖项id
    if ($rid != 6) {
        if ($prize_arr[$rid-1]['num']>1) {
            $prize_arr[$rid-1]['num']-=1;
            $redis->lSet('prize',0,json_encode($prize_arr));
            $prize = $prize_arr[$rid-1]['prize'];
            echo '恭喜您获得'.$prize;
        }else{
            $prize = $prize_arr[$rid-1]['prize'];
            unset($prize_arr[$rid-1]);//将中奖项从数组中剔除，剩下未中奖项
            $redis->lSet('prize',0,json_encode($prize_arr));
            echo '未中奖';
        }
    }else{
        echo  '未中奖';
    }
}


lotto();