<?php
    namespace Controllers;

    use Models\Blog;

    class BlogController {

        function blog(){
           
            // 引入模型
            $blog = new Blog;
            $blog->blog();
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
        
        // 生成静态页
        function staticPage (){

            $blog = new Blog;
            $arr = $blog->staticPage();

            // 开启缓存区
            ob_start();
            
            // 将数组中的内容循环出来
            foreach($arr as $v):
                // 加载视图
                view('blog.static',[
                    'blog' => $v,
                ]);

                // 取出缓存去的数据
                $str = ob_get_contents();

                // 将数据写入 /Public/staticPage/ 中
                file_put_contents(ROOT."Public/staticPage/".$v['id'].".html",$str);

                // 清空缓存区
                ob_clean();

            endforeach;

            
            echo "ok";
        }
    }