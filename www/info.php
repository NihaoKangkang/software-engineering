<?php
    session_start();
    if(empty($_SESSION))
    {
        header('Location: ./index.php'); // 登出  
      
        exit(0);
    }
    if(isset($_SESSION['expiretime'])) {  
  
        if($_SESSION['expiretime'] < time()) {  
      
            unset($_SESSION['expiretime']);  
      
            header('Location: ./index.php'); // 登出  
      
            exit(0);  
      
        } else {  
      
            $_SESSION['expiretime'] = time() + 600; // 刷新时间戳  
      
        }   
    }else{
        header('Location: ./index.php'); // 登出  
        exit(0);  
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = 'utf8' />
        <link href='./css/all.css' rel='stylesheet' type="text/css" />
        <title>兰州大学校车预订系统</title>
    <head>
    <body>
        <div class = 'bgp'>
            <h1 class='标题'>管理员系统</h1>
            <p> 车牌号：<?=empty($_GET['car_ID'])?$_GET['search_car_ID']:$_GET['car_ID']?></p>
        </div>

        <div class='out'>
            <table>
                <?php
                    $sql = mysqli_connect('127.0.0.1:3308', 'root', '');
                    if(!$sql)
                    {
                        exit('数据库连接失败，请联系管理员');
                    }
                    mysqli_set_charset($sql, 'utf8');
                    mysqli_select_db($sql, 'lzu');
                    if(!empty($_GET['car_ID'])) //如果是查预定信息 才需要分页 如果查车辆信息 因为只有一条
                    {
                        // 设置分页
                        $page = empty($_GET['page'])? 1 : $_GET['page'];
                        $query = 'select count(*) as count from book where car_ID="'.$_GET['car_ID'].'"';
                        $answer = mysqli_query($sql, $query);
                        $result = mysqli_fetch_assoc($answer);
                        $columnsRes = $result['count']; //查询总条数
                        $columns = 5; //每页显示数据
                        $pageRes = ceil($columnsRes / $columns); //总页数
                        $offset = ($page - 1) * $columns;
                        //分页结束 
                        $query = 'select student.stu_ID as stu_ID,student.stu_name as stu_name from book, student where student.stu_ID=book.stu_ID and book.car_ID = "'.$_GET['car_ID'].'" and book.begin_time = "'.$_GET['begin_time'].'" order by student.stu_ID limit '.$offset.', '.$columns.';';
                        $answer = mysqli_query($sql, $query);
                        //不可查看学生密码 这样的行为不好 而且密码在数据库中已经md5存储 无法查看 perfect~(md5要是可以加个salt就完美了 有空再完善)
                        //这里可以做取消某个学生的预定 但我觉得没那个必要
                        echo '<th>学生号</th><th>学生姓名</th>';
                        //如果传来参数 则删除对应班次
                        $count = 0;
                        while($result = mysqli_fetch_assoc($answer))
                        {
                            echo ($count%2==1)?'<tr class="trshuang">':'<tr class="trdan">';
                                echo '<td>'.$result['stu_ID'].'</td>';
                                echo '<td>'.$result['stu_name'].'</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }
                    else
                    {
                        $query = 'select * from car where car_ID = "'.$_GET['search_car_ID'].'";';
                        $answer = mysqli_query($sql, $query);
                        $result = mysqli_fetch_assoc($answer);
                        echo '<th>容量</th><th>驾驶员</th><th>联系电话</th>';
                        echo '<tr>';
                        echo '<td>'.$result['car_v'].'</td>';
                        echo '<td>'.$result['car_driver'].'</td>';
                        echo '<td>'.$result['car_phone'].'</td>';
                        echo '</tr>';
                    }
                    mysqli_close($sql);
                ?>
            </table>
            <br>
            <!--这句有bug 如果点击其他按键那么将无法返回管理员界面<a href='<?=""//$_SERVER["HTTP_REFERER"]?>'>返回</a>-->
            <a href='./adm.php'>返回</a>
            <br>
            <?php if(!empty($_GET['car_ID'])){ ?> <!-- 与分页对应 -->
            <a href="<?=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']?>">首页</a>&nbsp;&nbsp;&nbsp;<a href='info.php?page=<?= ($page - 1) < 1 ? 1 : ($page - 1); ?>&<?=$_SERVER['QUERY_STRING']?>'>上一页</a>&nbsp;&nbsp;&nbsp;
            <?=$page?> / <?= $pageRes?>
            &nbsp;&nbsp;&nbsp;<a href='info.php?page=<?php echo ($page + 1) > $pageRes ? $pageRes: ($page + 1); ?>&<?=$_SERVER['QUERY_STRING']?>'>下一页</a>&nbsp;&nbsp;&nbsp;<a href='info.php?page=<?php echo $pageRes; ?>&<?=$_SERVER['QUERY_STRING']?>'>尾页</a>
            <?php }?>
        </div>
        <div class = 'shenming'>
            该订票系统由兰州大学17级信安班 李俊杰，<a class='mylink' href='https://github.com/NihaoKangkang'>王硕</a>，魏兴宏&reg; 制作
        </div>
    </body>
</html>