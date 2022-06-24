<?php
session_start();
require_once ('db_connect.php');
if(isset($_REQUEST['ok']))
{
 $login = strip_data($_REQUEST['login']);
 $pass = strip_data(md5(md5($_REQUEST['pass'])));
    $sql=mysql_query("select * from users where login='$login' and pass='$pass' LIMIT 1");
	$userinfo=mysql_fetch_array($sql);
if($userinfo)
{
     $_SESSION['user_id']=$userinfo["id"];
     $_SESSION['login']=$userinfo["login"];
     $_SESSION['fio']=$userinfo["fio"];
     $_SESSION['owner']=$userinfo["adm"];
     $_SESSION['priem']=$userinfo["priem"];
     $_SESSION['actualy']=$userinfo["actualy"];
     $_SESSION['control']=$userinfo["control"];
     $_SESSION['auth']=$userinfo["pass"];
	header("location:index.php");
     }
      else
           {
	header('location:login.php?err');
       }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>ГОКУ ЦСПН по Печенгскому району</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" user-scalable="no" />
<link rel="shortcut icon" href="../../favicon.ico">
<link href="themes/css/bootstrap.min.css" rel="stylesheet">
<link href="themes/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="themes/css/font-awesome.min.css" rel="stylesheet">
<link href="themes/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<link href="themes/css/my_style.css" rel="stylesheet">
<link href="themes/css/jquery.dataTables.css" rel="stylesheet">
<script src="themes/js/jquery.js"></script>
<script src="themes/js/bootstrap.min.js"></script>
<script src="themes/js/validator.js"></script>
<script src="themes/js/jquery.form.min.js"></script>
<script src="themes/js/jquery.dataTables.min.js"></script>
</head>
<body>

<div class="form">
<h2>Программа ввода конвертов и марок</h2><br /><br />
   <form role="form" method="POST">
     <div class="form-group">
       <label>ИМЯ</label>
<select name="login" class="form-control">
        <option selected="selected"></option>
<?php $sql = "SELECT id,login FROM users where actualy=1 order by login";
$db = mysql_query($sql);
while($row = mysql_fetch_array($db))
{
  echo '<option value='.$row['login'].'>'.$row['login'].'</option>';
}
?>
</select>
 </div>

  <div class="form-group">
    <label for="exampleInputPassword1">PASSWORD</label>
    <input type="password" class="form-control" placeholder="Пароль"  required name="pass">
  </div>

 <input type="submit" value="Вход" name="ok" class="btn btn-default">
</form>
 <br />
 <p style="color:red;">
<?php
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}
   else{$ip=$_SERVER['REMOTE_ADDR'];}
 echo $ip.'<br />';
if(isset($_REQUEST["err"])){
	echo 'Неверные имя или пароль';
}
?>
<p>
  </div>


</body></html>





