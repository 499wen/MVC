<?php
    namespace Controllers;

    use Models\Blog;

    class BlogController {

        function blog(){
           
            // 引入模型
            $blog = new Blog;
            $arr = $blog->arr;
            $totalPage = $blog->totalPage;
            // 加载视图
            view("blog.blog",[
                "arr" => $arr,
                'totalPage' =>$totalPage,
            ]);
        }

        function insert (){

            // 模拟数据
            $blog = new Blog;
            $blog->insert();
        }

        function cs (){
            // $blog = new Blog;
            // $arr = getUrl('xzc','123');

            // var_dump($arr);
            view("user.user",[

            ]);
        }
    }