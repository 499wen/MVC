<?php
    namespace Controllers;

    use Models\Blog;
    use \libs\Redis;

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
            $blog = new Blog;
            $arr = $blog->staticPage();
            echo "<pre>";
            var_dump($arr[1]);

            // var_dump($arr);
            view("blog.static",[
                'blog' => $arr[1],
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

        // 将文章浏览量保存在 redis中
        function redis (){
            // 连接 Redis
            $redis = Redis::getInstance();
            // 获取文章id

            $id = (int)$_GET['id'] ;    

            // 拼出key 的值
            $key = "blog-$id";

            // 判断redis 中有没有$key
            if($redis->hexists("blog_play",$key)){
                // 有  就给对应的key  值+1
                $num = $redis->hincrby("blog_play",$key,1);
                echo $num;
            }else {
                // 没有就从数据库中取出
                $blog = new Blog;
                // var_dump($blog::$pdo);
                $stmt = $blog::$pdo->query("select play from mvc_blog where id = $id");
                $num = $stmt->fetch(\PDO::FETCH_ASSOC);
                var_dump($num['play']);

                // 保存到redis中
                $num = $redis->hset('blog_play', $key, $num['play']);
                echo $num;
            }

        }

        // 从redis中  取出数据 更新数据库
        function sqlToUpdate (){
            $blog = new Blog;

            // redis 取出数据
            $redis = Redis::getInstance();

            $arr = $redis->hgetall('blog_play');
            echo "<pre>";
            var_dump($arr);
            foreach($arr as $key => $v){
                // 将blog-2 的数字取出
                $key = explode("-",$key);

                // 更新数据库中
                $bool = $blog::$pdo->exec("update mvc_blog set play = {$v} where id = ".$key[1]);
                if($bool){
                    echo 'true';
                }else {
                    echo "false";
                }
            }
        }
    }