<?php
use workerman\Worker;
require_once ('./workerman/Autoloader.php');
require_once ('./db.php');
/*------------------------------------------
//使用http协议
// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker("http://127.0.0.1:2345");

// 启动4个进程对外提供服务
$http_worker->count = 4;

// 接收到浏览器发送的数据时回复hello world给浏览器
$http_worker->onMessage = function($connection, $data)
{
    // 向浏览器发送hello world
    $connection->send('hello world');
};

// 运行worker
Worker::runAll();
-------------------------------------------*/





/*-------------------------------------------
//使用websocket
// 创建一个Worker监听2346端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://127.0.0.1:2346");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $connection->send('hello ' . $connection->id);
};

// 运行worker
Worker::runAll();
------------------------------------------*/






/*-------------------------------------------------
// 使用tcp传输数据
// 创建一个Worker监听2347端口，不使用任何应用层协议
//$tcp_worker = new Worker("tcp://192.168.1.27:2347");
$tcp_worker = new Worker("tcp://0.0.0.0:33333");
// 启动4个进程对外提供服务
$tcp_worker->count = 4;

//连接时触发
$worker->onConnect = function($connection)
{
    //echo "new connection from ip " . $connection->getRemoteIp() . "\n";
	$connection->send('hello' . $data);
};

// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $connection->send('hello ' . $data);
};

// 运行worker
Worker::runAll();
------------------------------------------------*/


/*-----------------------------------------------
//使用websocket（广播方式）
$global_uid = 0;

//当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handle_connection($connection)
{
    global $text_worker, $global_uid;
    // 为这个链接分配一个uid
    $connection->uid = ++$global_uid;
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("user[{$connection->uid}] said: $data");
    }
}

// 当客户端断开时，广播给所有客户端
function handle_close($connection)
{
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("user[{$connection->uid}] logout");
    }
}
// 创建一个Worker监听2346端口，使用websocket协议通讯
$text_worker = new Worker("websocket://127.0.0.1:2346");

// 启动4个进程对外提供服务
$ws_worker->count = 1;

$text_worker->onConnect = 'handle_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';

// 运行worker
Worker::runAll();
-------------------------------------------------*/




/*-----------------------------------------------
//使用websocket（广播方式）
$global_uid = 0;

// 创建一个Worker监听2346端口，使用websocket协议通讯
$text_worker = new Worker("websocket://127.0.0.1:2346");

// 启动4个进程对外提供服务
$text_worker->count = 1;

//$text_worker->onConnect = 'handle_connection';
//$text_worker->onMessage = 'handle_message';
//$text_worker->onClose = 'handle_close';

//当客户端连上来时分配uid，并保存连接，并通知所有客户端
$text_worker->onConnect = function ($connection){
    global $text_worker, $global_uid;
    // 为这个链接分配一个uid
    $connection->uid = ++$global_uid;
};

// 当客户端发送消息过来时，转发给所有人
$text_worker->onMessage = function ($connection, $data){
    global $text_worker;
	//print_r($text_worker);
	//echo '</br>-----------------------------------------------';
    foreach($text_worker->connections as $conn){
        $conn->send($data);
    }
	//$text_worker->connections[$connection->id]->send("{$data}_socket_id:{$connection->id}");
};

// 当客户端断开时，广播给所有客户端
$text_worker->onClose = function ($connection){
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("logout");
    }
};

// 运行worker
Worker::runAll();
-------------------------------------------------*/





/*------------------------------------------------
//使用websocket（广播方式），多文件测试

//当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handle_connection($connection)
{
    global $text_worker;
    // 为这个链接分配一个uid
    //$connection->uid = ++$global_uid;
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
    global $text_worker;
	$temp_data = explode(',',$data);
	//foreach($temp_data as $key => $val){
		//$t = explode(':',$val);
		//$tmp[$t[0]] = $t[1];
	//}
	$tmp['v_id'] = 1;
	$tmp['u_id'] = 1;
	$tmp['u_name'] = 'test';
	$tmp['socket_id'] = $connection->id;
	//print_r($tmp);
	global $db;
	$db->setData($tmp);
	echo var_dump($db->add());
	//$connection->uid = $data['u_id'];
	//$connection->videoid = $data['video_id'];
	//$connection->username = $data['name'];
    foreach($text_worker->connections as $conn)
    {
		//print_r($conn);
        $conn->send("{$data}");

    }
}

// 当客户端断开时，广播给所有客户端
function handle_close($connection)
{
    global $text_worker;
    foreach($text_worker->connections as $conn)
    {
        $conn->send("logout");
    }
}
// 创建一个Worker监听2346端口，使用websocket协议通讯
$text_worker = new Worker("websocket://127.0.0.1:2346");
$db = new db();
// 启动4个进程对外提供服务
$text_worker->count = 1;

$text_worker->onConnect = 'handle_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';

// 运行worker
Worker::runAll();
-------------------------------------------------*/




///*--------------------------------------------------


// 创建一个Worker监听2346端口，使用websocket协议通讯
$text_worker = new Worker("websocket://127.0.0.1:2346");
$db = new db();
// 启动2个进程对外提供服务
$text_worker->count = 2;

//当客户端连上来时的回调函数
$text_worker->onConnect = function ($connection){
    //global $text_worker;
};

// 当客户端发送消息过来时，转发其所属用户组中的所有人
$text_worker->onMessage = function ($connection,$data){
    global $text_worker;
	global $db;

	//处理客户端发送过来的数据
	$temp_data = explode(',',$data);
	foreach($temp_data as $key => $val){
		$t = explode(':',$val);
		$tmp[$t[0]] = $t[1];
	}
	$tmp['socket_id'] = $connection->id;
	$db->setData($tmp);

	//插入映射表
	$db->add();

	//查找属于这个用户组的进程
	$send = $db->find();

	if($send){
		//当有多个进程属于同一个v_id组的时候，给各个进程发送信息
		foreach($send['socket_id'] as $socket_id){
			$text_worker->connections[$socket_id]->send("{$send['str']}...");
		}
	}else{
		//只发送信息给当前进程
		$connection->send($tmp['u_name']);
	}
};

// 当客户端断开时，广播给所有客户端
$text_worker->onClose = function ($connection){
    global $text_worker;
	global $db;
	//当断开连接时，删除映射表
	$db->del($connection->id);
};
// 运行worker
Worker::runAll();
//----------------------------------------------------*/
?>