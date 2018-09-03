<?php
    namespace Controllers;

    use Models\Index;
    use Models\Blog;

    class IndexController {

        function index(){

            $index = new Index;
            $str = $index->index();

            // 开启缓存区
            ob_start();

            // 加载视图
            view("index.index",[
                'index' => $str,
            ]);

            // 取出缓存区数据
            $str = ob_get_contents();

            // 将取出的数据放入 /Public/index.html中
            file_put_contents(ROOT."/Public/index.html",$str);

            // 清除缓存区
            ob_clean();

            // 直接跳转
            header("location:/index.html");
        }

        
        
    }
