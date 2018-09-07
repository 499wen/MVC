<?php
    /* 
        操作redis 列队
    */ 
    namespace Controllers;

    use libs\Redis;
    use libs\Mail;

    class QueueController {

        // 发送邮件
        function mail (){
            // 链接redis 服务器
            $redis = Redis::getInstance();

            // 设置 php 永不超时    - php 自带
            ini_set('default_socket_timeout', -1);

            echo "发邮件队列启动成功..\r\n";

            // 引入发邮件类
            $mail = new Mail;

            // 无限循环 从列表中取消息  并发邮件
            while(true){
                // 从队列中弹出（取出并删除）消息
                $data = $redis->brpop('email', 0);

                // 将取出的数据进行反序列化
                $message = json_decode($data[1], TRUE);
                $message[2] = "<a href='http://localhost:9999/user/success?code={$message[2]}'>点击激活码 跳转激活账号".$message[2]."</a>";
                var_dump($message[2]);
                // 发邮件
                $mail->send($message);

                echo "发送邮件成功！继续等待下一个。\r\n";
            }
        }
    }