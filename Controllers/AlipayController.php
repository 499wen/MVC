<?php
    namespace Controllers;

    use Yansongda\Pay\Pay;
    use Models\User;

    class AlipayController {

        // 支付账号： hgnvpu3964@sandbox.com
        // 配置
        public $config = [
            'app_id' => '2016091700531806',
            // 支付宝公钥
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwO1mvz/JRZ+2EZQHuQm/d41VlGebgLpBfLaWYUwQo0bCOuZbzore/Ttblt+C0zD6GE+joWxlMua2JI83WvWNFzLL8hladE7ewaaSgLGZYcECd+zitWU3GlS9fJepmJwpWCVYwgiSRwT0bRUKBu2b1aW/lZ4ND8xSO9ioIz3rFxbKgev/MiPLrvi5MrIA77E1I/x1nVuLcE2QufjK3ziFS0zNhtm9g1kgIVyJDiehqU6Vpxek49zfNqPTWsobBVVzkvX91D/ULxc3P2dYkmV07jnajcSU5vyMd8FOsbAopbEtXTWxlZ2OLgHLT26bUr7UYDkW9HT183B7VTf9bGxChwIDAQAB',
            // 商户应用密钥
            'private_key' => 'MIIEpAIBAAKCAQEA3559Q1jeJNs5TiOOJe95ZY+q7LU2xTC2708rXPMjwSx5jGpb+Lj4i2ECyO4S5dttocj5EmHzOD3rKCQttAvDPHnkz9rm+ZNltMItLVEhHeCUXH3CQtjchRka7GaM5ZmCRdpWX0KsRr/0DLoxgLzd1KC/ghwmbPoVneqdK3dfDVugMxvdvLmc8sbjO+g6p9rEuVpu6/0OhYMm8mI7Tb/1nYSTt2FMtsm+C94ryvD7wNDPRvC34VePg29MisjpHLl52Q3Ae/P9JTuFzEldsql6zcQtwndmSZaLkQyFLytcBtXD+fe2ftKgxdsf1kd3JF9w352E2Hxgf7DtfmbcWG0UhwIDAQABAoIBAGvRsBX70Eg3B19cl1pFJ+RHLCOrkDftsBactoy/ktq+RfDlC2lWTkhtwvj8iVIYxbh+/VDWt33DpRGauAqarU0IfMGXi0yEvEI2aa/y8SPFt+MMo9C1XUYzf57CJ2dA5+YkSwbtT8FhJhvI3rty1QaDZzMlvYVH44pbp+JgUQ1P3GGdW66HfazRWmGJ07F+tkmYFbe8Kw6OOcD0ameD+VZrSMSLxpUwOWT01u3fZY5hK3aXXw0Bl7IqFcbMRB0ioAe26U3jE/sJDHGH70e/E8yTLB3H9OSBkx7YKhH86fze+Pi1MC2ZBhUwffIzjROchdsGouiYS8gD14Owgr43FvECgYEA+1Q91elWssi5NtzjLw8WYbkvtGHmpaxcgWlYOqAQAKmtg/Wh69N6ul35CqAXOj63At4eI/QUxA7mW9jm10p4kY6k2sf0l2s00GVNA6VDJs6vn14R5CEXey/bV/YSF6GLDr60Y2p9rgiMNPl6JcE07MtSyjJ3AjxlR1gQkR3hvn8CgYEA48ZpMjSo82GdCUfCMI2j9UVqa5mBctThEXht+PdkVJBASmvb4v2VXP9BuQhiU+/atID5e/ewwlW6AvkL6BZM42DeHjiRXyW2Hm8PVc03cK/MjWmKVzErVnh7TmfYOGIC0ww1Qt648JUvVBh9cVi35mDxzJmAoTxSubf+gJRVtfkCgYEAwhbNZgu703elaRPuMiJRo4gyqffM+faAvcPPyf5gPVLL2BNyRz9bEN+PesAlD+rsxD8qQZO2Ea58AUQhCPPO5oGlF/vBDMpSz/jbH8yZbjfBcq5f3LAdhRlxJ521XSB41hs6DWhoxGR/ek2LIerx50ghmFqeg1CG2jDNInRUKBsCgYB92+PC1zBIE7cHKryVwZ5IaAvu2sqZniGjWq1BtcmdpHux47mDyuVelrlFOenDQbTSqrWwl2P3bhEzlVPL8Y6Y0azKfC1vqtHgLTQHEDdDMBDSTvAfT88Hn9lasKzcMuQmpAdGJPWCEp0tVb7zA7fSN91EVcmqzj9T2eRqPgzCWQKBgQD0Osy/0gRwMCfk8jz3+DxGanDv/vPYa3UA7vwiM0yz3ZcxKF/g5naWS0cniaKxMHEXkE2rjliJdwCpNM5FfdXd/wMNRQM93Wd77DrL3XetuHoEPUu4y0jw02wFgzKR4qZOtqaPSRekzvapN7Ra5nNP7EPtTQXFihEyT8FdsJTKtw==',
        
            // 通知地址
            'notify_url' => 'http://io.tunnel.echomod.cn/alipay/notify',
            // 跳回地址
            'return_url' => 'http://localhost:5500/alipay/return',
            
            // 沙箱模式（可选）
            'mode' => 'dev',
        ];

        function pay (){
            // 接收订单编号
            $code = $_POST['code'];
            // 取出订单信息
            $order = new \models\Order;
            // 根据订单编号取出订单信息
            $data = $order->findByCode($code);
            // echo "<pre>";
            // var_dump($data);die;
            // 如果订单还未支付就跳到支付宝
            if( $data['status'] == 0 )
            {
                // 跳转到支付宝
                $alipay = Pay::alipay($this->config)->web([
                    'out_trade_no' => $code,
                    'total_amount' => $data['money'],
                    'subject' => '智聊系统用户充值 ：'.$data['money'].'元',
                ]);
                $alipay->send();
            }
            else
            {
                die('订单状态不允许支付~');
            }
        }

        // 跳回地址
        function return (){
            // 验证数据是否是支付宝发过来
            $data = Pay::alipay($this->config)->verify();

            echo '已跳回!';

            sleep(3);

            header("location:/user/paymantPage");
        }

        // 接收支付成功返回的通知
        function notify (){
            // 生成支付类的对象
            $alipay = Pay::alipay($this->config);

            try{
                // 判断消息是否是支付宝发过来的，以及判断这个消息有没有被中途串改，如果被改了就抛出异常
                $data = $alipay->verify(); // 是的，验签就这么简单！
    
                // 判断支付状态
                if($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED')
                {
                    // 更新订单状态
                    $order = new \Models\Order;

                    // 获取订单信息   $data->out_trade_no 支付成功后返回的数据
                    $orderInfo = $order->findByCode($data->out_trade_no);
                    
                    // var_dump($orderInfo);die;
                    // 如果订单的状态为未支付状态 ，说明是第一次收到消息，更新订单状态 
                    if($orderInfo['status'] == 0)
                    {
                        // 开启事务
                        $order->startTrans();
    
                        // 设置订单为已支付状态
                        $ret1 = $order->setPaid($data->out_trade_no);
                        // 更新用户余额
                        $user = new \Models\User;
                        $ret2 = $user->addMoney($orderInfo['money'], $orderInfo['user_id']);

                        // 判断
                        if($ret1 && $ret2)
                        {
                            // 提交事务
                            $order->commit();
                        }
                        else
                        {
                            // 回事事务
                            $order->rollback();
                        }
    
                    }
                }
            } catch (\Exception $e) {
                die('非法的消息');
            }
            
            $alipay->success()->send();
        }

        // 支付宝退款
        function payRefund (){

            

            // 生成唯一退款订单号（以后使用这个订单号，可以到支付宝中查看退款的流程）
            $refundNo = md5( rand(1,99999) . microtime() );

            try{

                $code = $_POST['code'];
                $money = $_POST['money'];
                echo $code."+++++<br>153684169135045";
                $order = [
                    'out_trade_no' => '153684169135045',    // 退款的本地订单号
                    'refund_amount' => 0.01,              // 退款金额，单位元
                    'out_request_no' => $refundNo,     // 生成 的退款订单号
                ];
               
                // 退款
                $ret = Pay::alipay($this->config)->refund($order);
                // die;
                if($ret->code == 10000)
                {
                    echo '退款成功！';
                }
                else
                {
                    echo '失败' ;
                    var_dump($ret);
                }
            }
            catch(\Exception $e)
            {
                var_dump( $e->getMessage() );
            }
        }
    }