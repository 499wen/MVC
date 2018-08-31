<?php
    namespace Models;

    use PDO;
    class Blog {

        var $pdo;
        var $arr;
        var $totalPage;

        function __construct (){

            $this->pdo = new PDO("mysql:host=localhost;dbname=mvc","root","");
            $this->pdo->exec("set names utf8");

            $where = "1 = 1 ";

            // 搜索 - title content
            if(isset($_GET['search']) && $_GET['search']){
                $search = $_GET['search'];
                $where .= " and title like '%{$search}%' or content like '%{$search}%'";
            }
            
            // 搜索 - time_start
            if(isset($_GET['time_start']) && $_GET['time_start']){
                $time_start = $_GET['time_start'];
                $where .= "and created_at > '{$time_start}'";
            }

            // 搜索 - time_end
            if(isset($_GET['time_end']) && $_GET['time_end']){
                $time_end = $_GET['time_end'];
                $where .= "and created_at < '{$time_end}'";
            }

            // 排序 - 添加日期 - 降序 - desc
            if(isset($_GET['time_desc']) && $_GET['time_desc'] == 'desc') {
                $where .= "order by created_at desc ";
            }

            // 排序 - 添加日期 - 升序 - asc
            if(isset($_GET['time_asc']) && $_GET['time_asc'] == 'asc') {
                $where .= "order by created_at asc ";
            }

            // 排序 - 获赞数 - 升序 - asc
            if(isset($_GET['play_asc']) && $_GET['play_asc']){
                $where .= "order by play asc";
            }

            // 排序 - 获赞数 - 降序 - desc
            if(isset($_GET['play_desc']) && $_GET['play_desc']){
                $where .= "order by play desc";
            }

            /*    --------------    分页 - start    ---------------     */
            // 计算总数据
            $sql = "select id from mvc_blog where ".$where;  
            
            $stmt = $this->pdo->query($sql);

            $num = $stmt ->rowCount();
            // 当前页 
            $curPage = 1;
            if(isset($_GET['page'])){
                $curPage = $_GET['page'];
            }
            // echo $sql,$num; 
            // 显示记录数
            $curPage_column = 10;
            // 总页码
            $this->totalPage = ceil($num/$curPage_column);


            /*    --------------    分页 - end    ---------------     */

            // echo "select id,title,content,created_at,created_up,play from mvc_blog where {$where} limit 10";die;
            // 查询数据  
            $stmt = $this->pdo->query("select id,title,content,created_at,created_up,play from mvc_blog where {$where} limit ".($curPage-1)*$curPage_column.",".($curPage_column));

            if($stmt != false){
                $this->arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
            }else {
                
            }
        }

        // 往 blog 表中添加数据
        function insert (){
            
            for($i=0;$i<100;$i++){

                $author_id = rand(1,1000);
                $title = $this->getChar(rand(5,8));
                $content = $this->getChar(rand(20,50));
                $play = rand(50,500);
                $time = date("Y-m-d H:i",rand(1123548252,1535619247)) ;

                $temt = $this->pdo->prepare("insert into mvc_blog (id,author_id,title,content,play,created_at) values(null,?,?,?,?,?)");
            
                $aa = $temt->execute(array($author_id,$title,$content,$play,$time));
                var_dump($aa);
            }

        }

        function getChar($num)  // $num为生成汉字的数量
        {
            $b = '';
            for ($i=0; $i<$num; $i++) {
                // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
                $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
                // 转码
                $b .= iconv('GB2312', 'UTF-8', $a);
            }
            return $b;
        }

    }