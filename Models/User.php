<?php

    namespace Models;
    use libs\Mail;
    use libs\Redis;

    class User extends Base {

        function getName (){

            return "Tom";
        }

        // 注册
        function regist ($data = []){
            // 发送邮件
            // $mail = new Mail;
            // $success = $mail->send($data);

            // 引入 redis 类 连接redis 服务器
            $redis = Redis::getInstance();

            // 注册码
            $code = md5(rand(1,10000000));

            $redis->setex($code,300,json_encode($data));

            // 将数组转为 json 字符串
            $data[] = $code;

            $from = json_encode($data);

            // 将数据保存 队列中 、 字符串中
            $success = $redis->rpush("email",$from);

            // 邮件发送成功 将数据添加数据库
            // if($success){
            //     $stmt = self::$pdo->prepare("insert into mvc_user (id,email,password,created_at) values(null,?,?,now())");
            //     $stmt ->execute(array(
            //         $data[0],md5($data[1])
            //     ));
            //     echo "注册成功！ <a href='/user/loginPage'>点击跳转登录页面</a>";
            // } 
        }

        function success (){
            echo "<pre>";
            $code = $_GET['code'];

            // 取出 redis 服务器中的账号 
            $redis = Redis::getInstance();

            // 判断 redis 服务器中是否有 $code 这个键
            $bool = $redis->exists($code);
            // 取出对应的值
            $from = $redis->get($code);
            if(isset($form)){
                return "每个注册码只能用一次！";
            }
            $data = json_decode($from);

            if($bool){
                // 将 key 删除
                $redis->del($code);

                // 将数据存入数据库中
                $stmt = self::$pdo->prepare("insert into mvc_user (id,email,password,created_at) values(null,?,?,now())");
                $stmt->execute($data);

                
            }
        }

        // 处理登录的表单数据
        function login ($data){
            echo "<pre>";
            // 查看数据库中 是否有能匹配的数据
            $stmt = self::$pdo->prepare("select id,password from mvc_user where email = ?");
            $arr = $stmt->execute(array(
                $data[0],
            ));
            // var_dump($arr)
            $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
          
            if($arr['password'] == md5($data[1])){
                // 将id 、 email 保存在session 中
                $_SESSION['id'] = $arr['id'];
                $_SESSION['email'] = $data[0];

                return  true;
            }else {
                return false;
            }

        }

        
    }