<?php

ini_set('session.save_handler', 'redis');  // 使用 redis 保存session
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');  // 设置 redis 服务器地址
ini_set('session.gc_maxlifetime', 600);  // 设置session过期时间为10分钟
// 开启 session
session_start();

define("ROOT", dirname(__FILE__)."/../");
// 引入 composer 自动加载文件
require(ROOT.'vendor/autoload.php');
// 自动加载 函数
function autoload($class){
    $path = str_replace('\\', '/', $class);
    require(ROOT . $path . '.php');
}

spl_autoload_register("autoload");

    // 设置路由
    if( php_sapi_name() == "cli"){

        $controller = ucfirst($argv[1]);
        $active = $argv[2];

    }else {
        @$route = $_SERVER["PATH_INFO"];

        // 取出控制台  与  其对应方法
        if(isset($route) == "" || $route == "/"){
            $controller = "Index";
            $active = "index";
        }else {
            $controller = ucfirst(explode("/", $route)[1]);
            $active = explode("/", $route)[2];

        }
        
    }
// 拼接类名
$obj = "Controllers\\".$controller."Controller";

$a = new $obj;
$a->$active();
// 编写加载视图函数
function view($file , $data = []){

    $view = ROOT."Views/".str_replace('.' , '/' , $file).".html";

    // 变量覆盖 extract
    extract($data);
    require($view);
}

// 获取url 地址
function getUrl($except = []){
    // ['odby','odway']
    // 循环删除变量
    foreach($except as $v)
    {
        unset($_GET[$v]);

        // unset($_GET['odby']);
        // unset($_GET['odway']);
    }

    /*
    $_GET['keyword'] = 'xzb';
    $_GET['is_show] = 1

    // 拼出：  keyword=abc&is_show=1
    */

    $str = '';
    foreach($_GET as $k => $v)
    {
        $str .= "$k=$v&";
    }

    return $str;

}

// 配置数据
function config ($data){
    
    require(ROOT."config.php");

    return $config[$data];
}
?>