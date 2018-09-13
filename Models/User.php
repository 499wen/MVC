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
            $stmt = self::$pdo->prepare("select * from mvc_user where email = ?");
            $arr = $stmt->execute(array(
                $data[0],
            ));
            
            $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
            // var_dump($arr);die;
            if($arr['password'] == md5($data[1])){
                // 将id 、 email 、 balance 保存在session 中
                $_SESSION['user_id'] = $arr['id'];
                $_SESSION['email'] = $data[0];
                $_SESSION['balance'] = $arr['balance'];

                return  true;
            }else {
                return false;
            }

        }

        // 添加充值数据
        function recharge ($data){
            // 插入数据
            $stmt = self::$pdo->prepare("insert into mvc_order(money,user_id,order_code,status,created_at) values(?,?,?,0,now())");
            $bool = $stmt->execute($data);

            if($bool)
            {
                return true;
            }else {
                return false;
            }
        }

        // 查询所有账单
        function paymant ($user_id){
            // 查询数据
            $stmt = self::$pdo->query("select * from mvc_order where user_id = {$user_id} order by created_at desc");

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // 充值成功 - 更新用户余额
        function addMoney ($money,$id){
            $_SESSION['balance'] += $money*1;
            $stmt = self::$pdo->prepare("update mvc_user set balance = ? + balance where id = ?");
            return $stmt->execute([
                $money,$id
            ]);
        }
        
    }