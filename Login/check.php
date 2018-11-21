
<?php
require_once("ms_login.php");
//require_once ("mysql_connect.php");
$name=$_POST['name'];
$password=$_POST['password'];
if($name == "")
{
 
	echo "请填写用户名<br>";
     echo"<script type='text/javascript'>alert('请填写用户名');location='login.php';
			</script>";
    
    
     
 
}
elseif($password == "")
{
 
     //echo "请填写密码<br><a href='login.php'>返回</a>";
	echo"<script type='text/javascript'>alert('请填写密码');location='login.php';</script>";
	
}
else
{ 
	switch(judge($name, $password))
    {
      case 0:
        echo"<script type='text/javascript'>alert('无此用户，请先注册');location='login.php';</script>";
        break;
      case 1:
       // echo"<script type='text/javascript'>alert('密码错误');location='login.php';</script>";
        break;
      case 2:
        echo"<script type='text/javascript'>alert('登陆成功');location='welcome.html';</script>";
        break;
      default:
        echo"<script type='text/javascript'>alert('未知错误');location='welcome.html';</script>";
        break;
    }
    
 
}
?>
