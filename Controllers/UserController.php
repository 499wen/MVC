<?php
    namespace Controllers;

    use Models\User;
    use libs\Mail;
    use libs\Redis;

    class UserController {

        public function hello()
        {
            // 取数据
            $user = new User;
            $name = $user->getName();

            // 加载视图
            view('user.user', [
                'name' => $name
            ]);
        }
        // 注册 - 页面
        function registPage (){

            view("user.regist"); 
        }
        // 登录 - 页面
        function loginPage (){

            view("user.login"); 
        }
        // 注册 
        function regist (){
            // 接收传来的 email 、 password
            $email = $_POST['email'];
            $pwd = md5($_POST['password']);

            // 调用User类 处理数据
            $user = new User;
            $user->regist([$email,$pwd]);
        }

        // 登录
        function login (){
            // 接收传来的 email 、 password 、 _token
            $email = $_POST['email'];
            $pwd = $_POST['password'];
            // $_token = $_POST['_token'];

            // 调用 User类 处理数据
            $user = new User;
            $bool = $user->login([$email,$pwd]);
            
            if($bool){

                header("location:/");

            }else {
                echo "账号或密码错误！";
                header("location:/user/loginPage");
            }
        }

        // 退出
        function unLogin (){
            unset($_SESSION['user_id']);
            unset($_SESSION['email']);

            view("user.login");
        }

        // 充值金额页面
        function rechargePage (){
            
            view("user.recharge");
        }

        // 添加充值数据
        function recharge (){
            // 生产订单号
            $code = time()."".rand(0,99999);

            // 接收传来的参数
            $money = $_POST['money'];
            $user_id = $_SESSION['user_id'];

            $user = new User;
            $bool = $user->recharge([$money,$user_id,$code]);
            if($bool){
                header("location:/user/paymantPage");
            }else {
                echo "充值失败";
                header("location:/user/rechargePage");
            }
        }

        // 支付页面
        function paymantPage (){
            $user_id = $_SESSION['user_id'];

            $user = new User;
            $arr = $user->paymant($user_id);

            view('user.rechargeList',[
                "arr" => $arr,
            ]);
        }

        // 支付宝支付
        function alipay (){
            
        }

        // 测试
        function cs (){
            $a = new Mail;
            $a->send();
        }

        // 激活成功
        function success (){
            $user = new User;
            echo $user->success();

            // 数据插入成功后 立即跳转到登陆页面
            return header("location:/user/loginPage");
        }

    }

?>