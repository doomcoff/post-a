<?php
session_start ();
include('db_connect.php');
if((isset($_SESSION['user_id'])) & (isset($_SESSION['auth']))) {
     $sql=mysql_query("SELECT id,pass FROM users WHERE id='".$_SESSION['user_id']."' and pass='".$_SESSION['auth']."'");
       $userinfo = mysql_fetch_array($sql);
             if(strcmp($_SESSION['auth'],$userinfo['pass']) == 0) {
if ($_SESSION['owner'] == "1"){$hidden='';}
else {$hidden='style="display: none"';}
  } else { header('Location:login.php'); exit;}
      }
else  {header('Location:login.php'); exit;}
?>

