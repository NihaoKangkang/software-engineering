<!DOCTYPE html>
<html>
    <head>
        <meta charset = 'utf8' />
        <link href='./css/all.css' rel='stylesheet' type="text/css" />
        <title>兰州大学校车预订系统</title>
    <head>
    <body>
        <div class='bg'></div>
        <div class='shurukuang'>
            <h1>兰大校车订票系统</h1>
            <form action='login.php' method='POST' target='_top'>
                <input type='text' name='id' placeholder = '学生号/管理员账号' />
                <br>
                <br>
                <input type='password' name='password' placeholder = '密码' />
                <br>
                <br>
                <input type='radio' name='role' value='stu' checked='true' />学生
                <input type='radio' name='role' value='adm' />管理员
                <br>
                <br>
                <input type='submit' style = "font-size: 25px;" value='登陆，起飞~' />
            </form>
        </div>
        <div class = 'shenming'>
            该订票系统由兰州大学17级信安班 李俊杰，<a class='mylink' href='https://github.com/NihaoKangkang'>王硕</a>，魏兴宏&reg; 制作
        </div>
    </body>
</html>