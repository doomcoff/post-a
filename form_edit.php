<?php
include('session_start.php');  
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
<link href="themes/css/my_style.css" rel="stylesheet">
<script src="themes/js/jquery.js"></script>
<script src="themes/js/bootstrap.min.js"></script>
<script src="themes/js/validator.js"></script>
<script src="themes/js/jquery.form.min.js"></script>

<!--[if IE 7]>
<link rel="stylesheet" href="../themes/css/font-awesome-ie7.css">
<![endif]-->
<!--[if lt IE 9]>
<script src="../themes/js/html5.js"></script>
<![endif]-->

</head>
<body>

<div class="container-fluid">
    <div class="row">
         <div class="col-md-2">
<div class="panel panel-default m" id="mMenu">
    <div class="menucont"><br />
        <div class="btn btn-icon btn-lg"><a href="index.php"><i class="fa fa-envelope" aria-hidden="true"></i> Форма ввода</a></div>   
        <div class="btn btn-icon btn-lg"><a href="spr_show.php"><i class="glyphicon glyphicon-cog"></i> Справочник</a></div>
        <div class="btn btn-icon btn-lg"><a href="logout.php">  <i class="glyphicon glyphicon-off"></i>           Выйти </a><span class="small text-muted">(<?php echo $_SESSION['login']; ?>)</span></div>
      </div>
   </div>
         </div>

<div class="col-md-10">
    <div class="page-header">
       <h4>Добавить марки <small></small></h4>
            </div>


<!--<form id="addconv" role="form" method="POST" action="aaaaa1.php" onSubmit="return reviewsAdd(this);" >  -->
<form id="addconv" class="form-horizontal" role="form" method="POST" action="add.php?add=c" onSubmit="return reviewsAdd(this);" >



<div class="form-horizontal">
     <div class="form-group">
<?php
$query= ('
SELECT d.id, d.one_sum, d.value
   FROM resvalue  d
   JOIN (
     SELECT MAX(dates) dates, one_sum,id
       FROM resvalue where id_res=2 and value>0
      GROUP BY cut_name
   ) m ON (d.id = m.id) order by `one_sum`
');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {


echo'<div class="col-md-2">
    <label for="">'.$row['one_sum'].' <span class="label label-info">'.$row['value'].'</span></label>
    <input name="'.$row['id'].'" id="'.$row['id'].'" value="" class="form-control" placeholder=""  type="number"  max="'.$row['value'].'"/>
  </div>';
}
?>
              </div>

<div id="tbs">
 <?php
  $_POST["id"] = $_GET["id"];
echo '<input name="nid" value="'.$_POST["id"].'" class="hidden"  />';
echo '<table class="table table-striped link-load" cellspacing="0" cellpadding="0" border="0" id="tb">
<tbody>
<tr><th>ID</th>
			<th>Сокращенное наименование</th>
			<th>Цена</th>
			<th>Исх №</th>
            <th>Исх дата</th>
            <th>Вид</th>
			<th>Дата</th>
			<th>Марки</th>
</tr>';

$query= ('SELECT resvalue.`id` as resid,`id_res`,`id_name`,`cut_name`,`value`,`one_sum`,
envelope.`id` as envid,`id_resval`,`n_isx`,`d_isx`,`orders`,envelope.`dates`
from envelope
    left join resvalue on envelope.id_resval = resvalue.id
where  envelope.id ="'.$_POST["id"].'"');

 $result = mysql_query($query);
    $num_rows = mysql_num_rows($result);
for($i=1; $i <= $num_rows; $i++)
{ $row = mysql_fetch_array($result);
echo '<tr>
<td>'.$row["envid"].'</td>
<td>'.$row["cut_name"].'</td>
<td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
<td>'.$row["n_isx"].'</td>
<td>'.$row["d_isx"].'</td>
<td>'.($row["orders"]==1 ? 'Да' : 'Нет').'</td>
<td>'.date("d-m-Y", strtotime($row['dates'])).'
<input name="dt" value="'.$row['dates'].'" class="hidden"  />
</td>
<td>';

$query1= ('select stamp.`id` as stampid, resvalue.`id` as resid, stamp.`value` as rvalue,`id_name`,`one_sum`,`cut_name`,(stamp.`value`*one_sum) as total
from stamp
  left join resvalue on resvalue.id = stamp.id_resval
   where stamp.`id_envelope`='.$row["envid"].'');
       $result1 = mysql_query($query1);
            while ( $row1 = mysql_fetch_array($result1 )){
echo '  '.$row1["rvalue"].'шт. * '.number_format($row1["one_sum"], 2, '.', ' ').' р. = '.number_format($row1["total"], 2, '.', ' ').' р.<br />';
          }
echo '</td></tr>';
   }
echo '</tbody></table>';

?>


 </div>

<input type="submit" class="btn btn-primary" value="Внести" name="btn">&nbsp;&nbsp;
<input type="reset" class="btn btn-default" value="Очистить форму"><span id="otv" class="text-danger"></span>
</div>
   </form>

<div>
        </div>
   </div>
</div>

<script language="JavaScript" type="text/javascript">
$(function(){
$('#addconv').validator({
feedback: {
success: 'glyphicon-pencil',
error: 'fa-times'
     }
   });
});

$(document).ready(function() {
     $('#addconv').ajaxForm({
         target: '#otv',
        success: function() {
       $('#otv').fadeIn('slow');
  $("#tbs").load(location.href+" #tbs>*","");
  $("#addconv").trigger('reset');
//location.reload();
        }
    });
});
</script>
</body></html>
