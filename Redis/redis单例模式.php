<?php

/**
 * redis 的单例模式
 */
class RedisInstance
{
    /**
     * 类对象实例数组,共有静态变量
     * @var null
     */
    private static $_instance;

    /**
     * 数据库连接资源句柄
     * @var
     */
    private static $_connectSource;

    /**
     * 私有化构造函数，防止类外实例化
     * RedisConnect constructor.
     */
    private function __construct()
    {
        return self::$_instance;
    }

    /**
     * 私有化克隆函数，防止类外克隆对象
     */
    private function __clone()
    {
        return self::$_instance;
    }

    /**
     *  单例方法,用于访问实例的公共的静态方法
     * @return \Redis
     * @static
     */
    public static function getInstance($config)
    {
        if (!(self::$_instance instanceof Redis)) {
            self::$_instance = new Redis();
        }
        self::connect($config['host'],$config['port']);

        // auth()方法一定要在connect()方法之后调用，否则会出错
        self::$_instance ->auth($config['password']);
        return self::$_instance;
    }

    /**
     * Redis数据库是否连接成功
     * @return bool|string
     */
    public static function connect($host,$port){
        // 如果连接资源不存在，则进行资源连接
        if (!self::$_connectSource) {
            //@return bool TRUE on success, FALSE on error.
            self::$_connectSource = self::$_instance->connect($host,$port);
            // 没有资源返回
            if (!self::$_connectSource) {
                return 'Redis Server Connection Fail';
            }
        }
    }




}

//test
//$config = ['host'=>'127.0.0.1','port'=>6379,'password'=>'123456'];
//$redis = RedisInstance::getInstance($config);
//
//$redis->lPush('example',111111111);
//$redis->lPush('example',222222222);
//$redis->lPush('example',333333333);
//print_r($redis->lRange('example',0,-1));
