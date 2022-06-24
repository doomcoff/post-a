<?php
include('session_start.php');  
$url = $_SERVER['HTTP_REFERER'];
$_POST["add"] = $_GET["add"];

if ($_POST['add']=='a'){


  if (is_numeric($_POST['id'])) {
           $id = mysql_real_escape_string($_POST['id']);

$cut_name = strip_data($_POST['cut_name']);
$full_name = strip_data($_POST['full_name']);
$value = strip_data($_POST['value']);
$value_k = strip_data($_POST['value']);
$one_sum = strip_data(str_replace(',','.',$_POST['one_sum']));
$sum_k = ($one_sum*$value);

$query = "UPDATE resvalue SET
cut_name='$cut_name',
full_name='$full_name',
value='$value',
value_k='$value_k',
one_sum='$one_sum',
sum_k='$sum_k'
WHERE id = '$id'";
mysql_query($query) or die("Инфа не записана.");

echo "<script type='text/javascript'>alert('Данные изменены!');</script>";
echo "<script language='Javascript'>function reload() {location = \"spr_show.php\"}; setTimeout('reload()', 50);</script>$printmsg"; exit;
       }
       else {
          header("Location: $url");
       }
    }
 else {
    echo 'Не сраслось';
 }
?>

