<!DOCTYPE html>
<html>
    <head>
        <meta charset = 'utf8' />
        <link href='./css/all.css' rel='stylesheet' type="text/css" />
        <title>正在登陆……</title>
    <head>
    <body>
        <?php
            session_start();
            if(strlen($_POST['id']) * strlen($_POST['password']) == 0)
            {
                exit("<script>alert('用户名ID ,密码不能为空!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"); 
            }
            elseif(strlen($_POST['id']) != 12 && strlen($_POST['id']) != 6)
            {
                //用户名不是12位或6位 说明不是正常用户
                exit("<script>alert('数据库中没有您的信息 请联系管理员!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"); 
            }
            elseif(strlen($_POST['password']) < 6)
            {
                //密码小于六位 说明不是正常登陆
                exit("<script>alert('数据库中没有您的信息 请联系管理员!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"); 
            }
            $sql = mysqli_connect('127.0.0.1:3308', 'root', '');
            if(!$sql)
            {
                exit('数据库连接失败，请联系管理员');
            }
            mysqli_set_charset($sql, 'utf8');
            mysqli_select_db($sql, 'lzu');
            if($_POST['role'] == 'stu')
            {
                $query = 'select stu_pass as pass from student where stu_id = "'.$_POST['id'].'"';
            }
            elseif($_POST['role'] == 'adm')
            {
                $query = 'select adm_pass as pass from admin where adm_id = "'.$_POST['id'].'"';
            }
            else{
                echo "<script>alert('禁止非法访问!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
            }
            $answer = mysqli_query($sql, $query);
            $result = mysqli_fetch_assoc($answer);
            if ($result['pass'] == md5($_POST['password']))
            {
                if($_POST['role'] == 'stu')
                {
                    $return = './stu.php';
                }
                else
                {
                    $return = './adm.php';
                }
                $_SESSION['id'] = $_POST['id'];
                $_SESSION['expiretime'] = time() + 600;
                if(isset($_SESSION['expiretime'])) {  
  
                    if($_SESSION['expiretime'] < time()) {  
                  
                        unset($_SESSION['expiretime']);  
                  
                        header('Location: ./index.php'); // 登出  
                  
                        exit(0);  
                  
                    } else {  
                  
                        $_SESSION['expiretime'] = time() + 600; // 刷新时间戳  
                  
                    }  
                  
                }
                header("refresh:3;url=$return"); 
        ?>
            <div class='bg'></div>
            <div class='shurukuang'>
                <h1>登录成功~</h1>
            </div>

        <?php
            }
            else
            {
                echo "<script>alert('密码或用户名错误！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
            mysqli_close($sql);
        ?>
        <div class = 'shenming'>
            该订票系统由兰州大学17级信安班 李俊杰，<a class='mylink' href='https://github.com/NihaoKangkang'>王硕</a>，魏兴宏&reg; 制作
        </div>
    </body>
</html>