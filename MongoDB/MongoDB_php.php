<?php
$MONGO_SERVER = array(
    'host'=>'127.0.0.1',
    'port'=>27017,
    'dbname'=>'club',
    'user'=>'',
    'pwd'=>'');


$host_port = $MONGO_SERVER['host'] . ":" . $MONGO_SERVER['port'];        // '10.123.55.16:20517'

$conn = new MongoClient($host_port);//连接远程主机
$db = $conn->selectDB($MONGO_SERVER['dbname']);//选择数据库，如果以前没该数据库会自动创建
/**********************这种方式也可以*********************/
$db = $conn->club;//如果以前没该数据库会自动创建
$collection = $db->users;          // 选择集合（表名：users）
/*******************************************************/

//$collName = 'users';
//$coll = new MongoCollection($db, $collName);//新建集合

//$count = $coll->count();//集合文档数量
//print("count: " . $count);


/**************************CURD操作*********************/
//$user = array('name' => 'caleng', 'email' => 'admin@admin.com');             // 新增
//$collection->insert($user);

//$newdata = array('$set' => array("email" => "test@test.com"));               // 修改
//$collection->update(array("name" => "caleng"), $newdata);

//$collection->remove(array('name'=>'caleng'), array("justOne" => true));      // 删除
//$collection->remove();//删除所有文档；

//$cursor = $collection->find();                                               // 查找=====结果是个对象，且只有循环才能查看
//foreach ($cursor as $row) {
//    print_r($row);
//    echo "<hr>";
//}
//var_dump($cursor);//无法打印出结果
//$user = $collection->findOne(array('name' => 'caleng'), array('email'));      // 查找一条====结果是个数组
//var_dump($user);die;
/*******************************************************/

//$collection->createIndex(array('a'=>1));//创建索引








$conn->close();//关闭连接