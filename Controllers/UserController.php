<?php
    namespace Controllers;

    use Models\User;

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


    }

?>