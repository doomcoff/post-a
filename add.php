<?php
include('session_start.php'); 
$url = $_SERVER['HTTP_REFERER'];
$_POST["add"] = $_GET["add"];
$user = $_SESSION['login'];

if ($_POST['add']=='a'){
       if ( empty($_POST['id_res']) || empty($_POST['value']) || empty($_POST['one_sum']) || empty($_POST['cut_name']) || empty($_POST['full_name']))
        {
echo "<script type='text/javascript'>alert('И чего почем зря, батон давить! Заполните все обязательные поля');</script>";
echo "<script language='Javascript'>function reload() {location = \"$url\"}; setTimeout('reload()', 50);</script>$printmsg"; exit;
        }
      else {
$id_res = strip_data($_POST['id_res']);
   if  ($id_res==1){$id_name = 'Конверт';} else {$id_name = 'Марки';}
$labeled = strip_data($_POST['labeled']);

$cut_name = strip_data($_POST['cut_name']);
$full_name = strip_data($_POST['full_name']);

$value = strip_data($_POST['value']);
$value_k = strip_data($_POST['value']);
$one_sum = strip_data(str_replace(',','.',$_POST['one_sum']));
$sum_k = ($one_sum*$value);
$dates = date("Y-m-d");

$res = mysql_query("INSERT into resvalue (id_res,id_name,labeled,cut_name,full_name,value,value_k,one_sum,sum_k,dates,user)
Values   ('$id_res','$id_name','$labeled','$cut_name','$full_name','$value','$value_k','$one_sum','$sum_k','$dates','$user')");
//echo "<script type='text/javascript'>alert('Данные внесены');</script>";
        }
     }
//--------==============
if ($_POST['add']=='b'){

if (isset($_POST)){
foreach ($_POST as $keyr=>$valuer){

if(is_numeric($keyr)){
    if($valuer>0){
            $queryr = ('select * from resvalue where id = '.$keyr.'');
            $resultr = mysql_query($queryr);
            $rowr = mysql_fetch_array($resultr);
            $newvalr=($rowr["value"]-$valuer);
         if ($newvalr<0){
 echo "<script type='text/javascript'>alert('Внимание! Количество марок недостаточно! Остатки можно добавить позже.');</script>";
 exit;
                       }
                    }
                 }
              }

$nisx = strip_data($_POST['nisx']);
$ndates = date("Y-m-d", strtotime($_POST['ndates']));
$orders = strip_data($_POST['orders']);
$envelope = strip_data($_POST['envelope']);

$env = mysql_query("INSERT into envelope (id_resval, n_isx, d_isx, orders, dates) Values ('$envelope','$nisx','$ndates','$orders','$ndates')");

    $nid = "select id from envelope order by id desc";
           $result = mysql_query($nid);
           $row = mysql_fetch_array($result);
           $newid=$row['id'];

$zap = "SELECT value,value_k,one_sum,labeled FROM resvalue where id = $envelope";
$result = mysql_query($zap);
$row = mysql_fetch_array($result);
$newclv=($row['value']-1);
$newclv_k=($row['value_k']);
$newsum=($row['one_sum']);
$labeled=($row['labeled']);

$query = "update resvalue set value='$newclv' where id='$envelope'";
mysql_query($query) or die("Инфа не записана upd value.");

$query = "update envelope set nclv='$newclv_k',nsum='$newsum',labeled='$labeled' where id='$newid'";
mysql_query($query) or die("Инфа не записана upd clv.");

if (isset($_POST)){
    foreach ($_POST as $key=>$value){
        if(is_numeric($key)) {
              if($value==0) { }
                    else  {
                   if($value>0) {
$stamp = mysql_query("INSERT into stamp (id_envelope,id_resval,value,dates) Values ('$newid','$key','$value','$ndates')");

$query1 = ('select * from resvalue where id = '.$key.'');
     $result1 = mysql_query($query1);
          $row1 = mysql_fetch_array($result1);
$newvalm=($row1["value"]-$value);
$newvalm_k=($row1["value"]);
$newsum=$row1["one_sum"];
//$newsumitog=($newvalm*$newsum);

$upm = ('update resvalue set value='.$newvalm.' where id='.$key.'');
   mysql_query($upm) or die("Инфа не записана1.");

$upm1 = ('update stamp set nominal='.$newsum.', res_clv='.$newvalm_k.' where id_envelope='.$newid.' and id_resval='.$key.'');
     mysql_query($upm1) or die("Инфа не записана2.");


               }
           }
       }
    }
 }
//echo "<script type='text/javascript'>alert('Данные внесены')</script>";
//echo "<script language='Javascript'>function reload() {location = \"$url\"}; setTimeout('reload()', 50);</script>$printmsg"; exit;
     }
 }
//-------------======================
if ($_POST['add']=='c'){
if (is_numeric($_POST['nid'])) {
             $newid = strip_data($_POST['nid']);
             $dt = strip_data($_POST['dt']);

if (isset($_POST)){
    foreach ($_POST as $key=>$value){
        if(is_numeric($key)) {
                   if($value>0) {
$stamp = mysql_query("INSERT into stamp (id_envelope,id_resval,value,dates) Values ('$newid','$key','$value','$dt')");

$query1 = ('select * from resvalue where id = '.$key.'');
     $result1 = mysql_query($query1);
          $row1 = mysql_fetch_array($result1);
$newvalm=($row1["value"]-$value);
$newvalm_k=($row1["value"]);
$newsum=$row1["one_sum"];
//$newsumitog=($newvalm*$newsum);

$upm = ('update resvalue set value='.$newvalm.' where id='.$key.'');
   mysql_query($upm) or die("Инфа не записана1.");

$upm1 = ('update stamp set nominal='.$newsum.', res_clv='.$newvalm_k.' where id_envelope='.$newid.' and id_resval='.$key.'');
     mysql_query($upm1) or die("Инфа не записана2.");

               }
           }
       }
   }

        }
}

?>
