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
            <h1 class='标题'>学生订阅系统</h1>
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
                    // 设置分页
                    $page = empty($_GET['page'])? 1 : $_GET['page'];
                    $query = 'select count(*) as count from clock';
                    $answer = mysqli_query($sql, $query);
                    $result = mysqli_fetch_assoc($answer);
                    $columnsRes = $result['count']; //查询总条数
                    $columns = 5; //每页显示数据
                    $pageRes = ceil($columnsRes / $columns); //总页数
                    $offset = ($page - 1) * $columns;
                    //分页结束 
                    $query = 'select * from clock order by begin_time limit '.$offset.', '.$columns.'';
                    $answer = mysqli_query($sql, $query);
                    echo '<th>车牌号</th><th>发车地点</th><th>发车时间</th><th>剩余座位</th><th>预订</th>';
                    //如果get['car_ID']参数中有值 那么添加到book表
                    if(!empty($_GET['car_ID']))
                    {
                        $query_before = 'select count(*) as c book where car_ID="'.$_GET['car_ID'].'" and stu_ID = "'.$_SESSION['id'].'" and begin_time="'.$_GET['begin_time'].'";';
                        if(!mysqli_fetch_assoc(mysqli_query($sql,$query))['c'])
                        {
                            $query_book = 'insert into book(car_ID, stu_ID, begin_time) values("'.$_GET['car_ID'].'","'.$_SESSION['id'].'","'.$_GET['begin_time'].'")';
                            mysqli_query($sql, $query_book);
                        }
                    }
                    //如果传入的是取消预定参数
                    if(!empty($_GET['car_ID_cancel']))
                    {
                        $query_before = 'select count(*) as c book where car_ID="'.$_GET['car_ID_cancel'].'" and stu_ID = "'.$_SESSION['id'].'" and begin_time="'.$_GET['begin_time'].'";';
                        if(mysqli_fetch_assoc(mysqli_query($sql,$query))['c'])
                        {
                            $query_book = 'delete from book where stu_ID="'.$_SESSION['id'].'" and car_ID = "'.$_GET['car_ID_cancel'].'" and begin_time = "'.$_GET['begin_time'].'"';
                            mysqli_query($sql, $query_book);
                        }
                    }
                    $count = 0;
                    while($result = mysqli_fetch_assoc($answer))
                    {
                        echo ($count%2==1)?'<tr class="trshuang">':'<tr class="trdan">';
                            echo '<td>'.$result['car_ID'].'</td>';
                            echo '<td>'.$result['begin_place'].'</td>';
                            echo '<td>'.$result['begin_time'].'</td>';
                            //查询剩余座位
                            $query_bk = 'select count(*) as bk from book where car_ID = "'.$result['car_ID'].'" and begin_time="'.$result['begin_time'].'"';
                            $answer_bk = mysqli_query($sql, $query_bk);
                            $result_bk = mysqli_fetch_assoc($answer_bk)['bk']; //已经订了的人数
                            $query_car = 'select car_v from car where car_ID = "'.$result['car_ID'].'"';
                            $answer_car = mysqli_query($sql, $query_car);
                            $result_car = mysqli_fetch_assoc($answer_car)['car_v']; //总容量
                            echo '<td>'.($result_car-$result_bk).'</td>';

                            $query_new = 'select count(*) as count from book where stu_ID = "'.$_SESSION['id'].'" and car_ID = "'.$result['car_ID'].'" and begin_time = "'.$result['begin_time'].'"';
                            $answer_temp = mysqli_query($sql, $query_new);
                            if (mysqli_fetch_assoc($answer_temp)['count'] != 0) //如果已经订票
                            {
                                echo '<td>预订成功(<a href = "'.$_SERVER['PHP_SELF'].'?page='.$page.'&car_ID_cancel='.$result['car_ID'].'&begin_time='.$result['begin_time'].'">取消</a>)</td>';
                            }
                            else
                            {   
                                //如果满了 就显示满了 联系管理员
                                if($result_car-$result_bk > 0)
                                {
                                    echo '<td><a href = "'.$_SERVER['PHP_SELF'].'?page='.$page.'&car_ID='.$result['car_ID'].'&begin_time='.$result['begin_time'].'">&nbsp;&nbsp;√&nbsp;&nbsp;</a>';
                                }                   
                                else
                                {
                                    echo '<td>车辆已经满载，请联系<a href = "https://github.com/NihaoKangkang">管理员</a>';
                                }
                            }
                        echo '</tr>';
                        $count += 1;
                    }
                    mysqli_close($sql);
                ?>
            </table>
        </div>
        <div class = 'forFix'>
            <br>
            <a href='stu.php'>首页</a>&nbsp;&nbsp;&nbsp;<a href='stu.php?page=<?= ($page - 1) < 1 ? 1 : ($page - 1); ?>'>上一页</a>&nbsp;&nbsp;&nbsp;
            <?=$page?> / <?= $pageRes?>
            &nbsp;&nbsp;&nbsp;<a href='stu.php?page=<?php echo ($page + 1) > $pageRes ? $pageRes: ($page + 1); ?>'>下一页</a>&nbsp;&nbsp;&nbsp;<a href='stu.php?page=<?php echo $pageRes; ?>'>尾页</a>
        </div>
        <div class = 'shenming'>
            该订票系统由兰州大学17级信安班 李俊杰，<a class='mylink' href='https://github.com/NihaoKangkang'>王硕</a>，魏兴宏&reg; 制作
        </div>
    </body>
</html>