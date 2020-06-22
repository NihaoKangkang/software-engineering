<?php
    session_start();
    if(isset($_SESSION['expiretime'])) {  
  
        if($_SESSION['expiretime'] < time()) {  
      
            unset($_SESSION['expiretime']);  
      
            header('Location: ./index.php'); // 登出  
      
            exit(0);  
      
        } else {  
      
            $_SESSION['expiretime'] = time() + 600; // 刷新时间戳  
      
        }   
    }
    /*
    var_dump($_GET);
    var_dump(empty($_GET['car_ID']));
    var_dump(empty($_GET['begin_place']));
    var_dump(empty($_GET['begin_time']));
    */
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
        </div>

        <div class='out'>
            <!-- 还有一个剩余容量bug -->
            <table>
                <?php
                    $sql = mysqli_connect('127.0.0.1:3308', 'root', '');
                    if(!$sql)
                    {
                        exit('数据库连接失败，请联系管理员');
                    }
                    mysqli_set_charset($sql, 'utf8');
                    mysqli_select_db($sql, 'lzu');
                    //这里设计一个添加行程的功能 可以及时的显示到网页上 不用二次刷新才能显示新增行程
                    if(!empty($_POST['car_ID']) && !empty($_POST['begin_place']) && !empty($_POST['date']) )
                    {
                        $query = 'insert into clock(car_ID, begin_place, begin_time) values("'.$_POST['car_ID'].'", "'.$_POST['begin_place'].'","'.$_POST['date'].' '.$_POST['time'].'");';
                        mysqli_query($sql, $query);
                    }
                    //这里再设计一个删除行程的功能 理由同上
                    if(!empty($_GET['car_ID']) && !empty($_GET['begin_place']) && !empty($_GET['begin_time']))
                    {
                        
                        $query_new = 'delete from clock where car_ID = "'.$_GET['car_ID'].'" and begin_place = "'.$_GET['begin_place'].'" and begin_time = "'.$_GET['begin_time'].'"';
                        mysqli_query($sql, $query_new);
                    }
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
                    echo '<th>车牌号</th><th>发车地点</th><th>发车时间</th><th>剩余容量</th><th>操作</th>';
                    $count = 0; //控制列表颜色的参数 不要动
                    while($result = mysqli_fetch_assoc($answer))
                    {
                        echo ($count%2==1)?'<tr class="trshuang">':'<tr class="trdan">';
                        //点击车牌号可以看到车辆具体信息
                        echo '<td><a href="./info.php?search_car_ID='.$result['car_ID'].'">'.$result['car_ID'].'</a></td>';
                        echo '<td>'.$result['begin_place'].'</td>';
                        echo '<td>'.$result['begin_time'].'</td>';
                        //查询剩余容量;
                        $query_bk = 'select count(*) as bk from book where car_ID = "'.$result['car_ID'].'" and begin_time="'.$result['begin_time'].'"';
                        $answer_bk = mysqli_query($sql, $query_bk);
                        $result_bk = mysqli_fetch_assoc($answer_bk)['bk']; //已经订了的人数
                        $query_car = 'select car_v from car where car_ID = "'.$result['car_ID'].'"';
                        $answer_car = mysqli_query($sql, $query_car);
                        $result_car = mysqli_fetch_assoc($answer_car)['car_v']; //总容量
                        //点击可以查看具体是哪个学生预定了这辆车
                        echo '<td><a href="./info.php?car_ID='.$result['car_ID'].'&begin_time='.$result['begin_time'].'">'.($result_car-$result_bk).'</a></td>';
                        echo '<td><a href = "'.$_SERVER['PHP_SELF'].'?page='.$page.'&car_ID='.$result['car_ID'].'&begin_place='.$result['begin_place'].'&begin_time='.$result['begin_time'].'">删除</a>行程</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                    
                ?>
            </table>
        </div>
        <div class = 'forFix'>
            <br>
                <!-- 设置一个表单增加班次 -->
                <form action = 'adm.php' method = 'POST' target='_top'> 
                    车辆：<select name='car_ID'>
                        <!-- 这里用php是为了以后升级方便 直接在数据库中添加车辆信息 这里便可以显示出来 -->
                        <?php
                            $query_car = 'select car_ID from car;';
                            $answer_car = mysqli_query($sql, $query_car); 
                            $result_c = mysqli_fetch_assoc($answer_car)['car_ID'];
                            echo '<option value="'.$result_c.'" selected="selected">'.$result_c.'</option>';
                            while($result_c = mysqli_fetch_assoc($answer_car)['car_ID'])
                            {
                                echo '<option value="'.$result_c.'">'.$result_c.'</option>';
                            }
                            mysqli_close($sql);
                        ?>
                    </select>
                    始发地：<select name='begin_place'>
                        <!-- 因为数据库中没有做发车地点的表 而直接从clock表中找发车地点有丢失的可能 所以这个选线固话 如果发车地点改变需要更改这块php代码 -->
                        <option value="Aplace" selected="selected">Aplace</option>
                        <option value="Bplace">Bplace</option>
                        <option value="Cplace">Cplace</option>
                    </select>
                    <!-- 火狐浏览器不能很好的兼容下面两条语句 需要用js改进 但是现在我还不会 -->
                    发车日期：<input type="date" name = 'date' />
                    发车时间：<input type="time" name = 'time' />
                    <input type='submit' value='添加该计划'>
                </form>
            <br>
            <a href='adm.php'>首页</a>&nbsp;&nbsp;&nbsp;<a href='adm.php?page=<?= ($page - 1) < 1 ? 1 : ($page - 1); ?>'>上一页</a>&nbsp;&nbsp;&nbsp;
            <?=$page?> / <?= $pageRes?>
            &nbsp;&nbsp;&nbsp;<a href='adm.php?page=<?php echo ($page + 1) > $pageRes ? $pageRes: ($page + 1); ?>'>下一页</a>&nbsp;&nbsp;&nbsp;<a href='adm.php?page=<?php echo $pageRes; ?>'>尾页</a>
        </div>
        <div class = 'shenming'>
            该订票系统由兰州大学17级信安班 李俊杰，<a class='mylink' href='https://github.com/NihaoKangkang'>王硕</a>，魏兴宏&reg; 制作
        </div>
    </body>
</html>