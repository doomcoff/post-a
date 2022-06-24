<?php
include('session_start.php');  
include('fun.php');
$url = $_SERVER['HTTP_REFERER'];
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
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
    $('#tblscroll').DataTable( {

            dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'print'
        ],
        deferRender:    true,
        scrollY:        350,
        scrollCollapse: true,
        paging:         false,
        scroller:       true,
     "language": {
"sLengthMenu": "Показать _MENU_ записей",
	"sZeroRecords": "К сожадению ничего не найдено",
	"sInfo": "Показано _START_ - _END_ из _TOTAL_ записей",
	"sInfoEmpty": "Показано 0 - 0 из 0 записей",
	"sInfoFiltered": "( Всего _MAX_ записей )",
	"sSearch": "Поиск:",
	"oPaginate": {
		"sPrevious": "<<",
		"sNext": ">>",
		"sFirst": "{",
		"sLast": "}"
	}
        }

    } );
} );
</script>
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


<div class="panel panel-default m">
    <div class="menucont"><br />
        <div class="btn btn-icon btn-lg"><a href="index.php">   <i class="fa fa-envelope" aria-hidden="true"></i> Форма ввода</a></div>
        <div class="btn btn-icon btn-lg"><a href="spr_show.php"><i class="glyphicon glyphicon-cog"></i>           Справочник</a></div>
        <div class="btn btn-icon btn-lg"><a href="logout.php">  <i class="glyphicon glyphicon-off"></i>           Выйти </a><span class="small text-muted">(<?php echo $_SESSION['login']; ?>)</span></div>
      </div>
   </div>




<div class="box-content">
Расход тмц по месяцам
    <div id="link-load">
        <ul class="list-group">

 </ul>
   </div>
     </div>
      </div>




<div class="col-md-10">
    <span id="divLoad">
       <div class="page-header">
          <h4>Форма ввода<small></small></h4>
            </div>
<form id="addconv" class="form-horizontal" role="form" method="POST" action="add.php?add=b" onSubmit="return reviewsAdd(this);" >



<div class="form-horizontal">
     <div class="form-group" id="stamp">
         <?php
$query= ('SELECT d.id, d.one_sum, d.value
   FROM resvalue  d
   JOIN (
     SELECT MAX(dates) dates, one_sum,id
       FROM resvalue where id_res=2 and value>0
      GROUP BY one_sum
   ) m ON (d.id = m.id) order by `one_sum`');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {

echo'<div class="col-md-2">
    <label for="">'.$row['one_sum'].'
    '.($row['value']<10 ? '<span class="label label-danger">'.$row['value'].'</span>' : '<span class="label label-info">'.$row['value'].'</span>').'

    </label>
    <input  type="number" name="'.$row['id'].'" id="'.$row['id'].'" value="" class="form-control" placeholder=""  max="'.$row['value'].'"/>
  </div>';
}
$_POST["id"] = $_GET["id"];
if (!isset($_POST["id"])){
  $isx='';
  $disx=date('d-m-Y');
      }
else{
$k = explode(":", $_POST["id"]);
$isx='isx'.$k[0];
$disx=date('d-m-Y', strtotime($k[1]));
}
?>
              </div>

<div class="jumbotron">
    <div class="form-group">
    <label for="input" class="col-xs-2 control-label">№ исходящего <span class="text-danger"> *</span></label>
      <div class="col-xs-2">
           <input name="nisx" id="nisx" value="<?php echo $isx; ?>" class="form-control" required/>
      </div>


 <label for="input" class="col-xs-1 control-label">Дата <span class="text-danger"> *</span></label>



  <div class="form-group col-xs-2">
                <div class="input-group date" id="dt">
                    <input name="ndates" id="ndates" value="<?php echo $disx; ?>" class="form-control" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
 </div>


 <div class="form-group">
  <label for="input" class="col-xs-2 control-label">Заказное <span class="text-danger"> *</span></label>
      <div class="col-xs-2">
            <select class="form-control" name="orders" id="orders" required>
<option value="" selected="selected"></option>
<option value="1">Да</option>
<option value="0">Нет</option>
      </select>
      </div>
</div>




<div class="form-group">
  <label for="" class="col-xs-2 control-label">Конверт<span class="text-danger"> *</span></label>
      <div class="col-xs-5">
            <select class="form-control" name="envelope" id="envelope" required>
                <option value="" selected="selected"></option>
<?php $sql = 'SELECT a.id, a.cut_name, a.value
   FROM resvalue a
   JOIN (
     SELECT MAX(dates) dates, cut_name, id
       FROM resvalue where id_res=1 and value>0
      GROUP BY cut_name
   ) d ON (a.id = d.id) order by `one_sum`';
$db = mysql_query($sql);
    while($row = mysql_fetch_array($db))
      {

//$col[]=$row['cut_name'].' <span class="label label-info">'.$row['value'].'</span>&nbsp;&nbsp;&nbsp;';
$col[]=$row['cut_name'].' '.($row['value']<10 ? '<span class="label label-danger">'.$row['value'].'</span>' : '<span class="label label-info">'.$row['value'].'</span>').'&nbsp;&nbsp;&nbsp;';


  if($row['value']<10){
    $min[]=($row['cut_name'].'&nbsp;&nbsp;<span class="label label-danger">'.$row['value'].' шт.</span>');
        }
echo '<option value='.$row['id'].'>'.$row['cut_name'].' ('.$row['value'].' шт.)</option>';}
?>
</select>    <?php foreach($col as $a=>$aa) { echo  $aa;}?>
      </div>
 </div>

<input type="submit" class="btn btn-primary" value="Внести" name="btn">&nbsp;&nbsp;
<input type="reset" class="btn btn-default" value="Очистить форму"><span id="otv"></span>
</div>
   </form>
<div>

<table id="tblscroll" class="display table table-striped link-load" style="width:100%">
<thead>
<tr class="warning"><th>№</th>
<th>ID</th>
<th>Дата</th>
			<th>Маркировка</th>
			<th>Сокращенное наименование</th>
			<th>Цена конверта</th>
			<th>Исх №</th>
            <th>Исх дата</th>
            <th>Заказной</th>
			<th>Марки</th>
            <th <?php echo $hidden; ?>><i class="glyphicon glyphicon-trash"></i></th>
</tr>
<thead>
<tbody>


<?php
$query= ('select resvalue.`id` as resid,
CASE WHEN envelope.labeled=1 THEN "Да" WHEN envelope.labeled=2 THEN "" WHEN envelope.labeled=3 THEN "А" ELSE 0 END nemark,
`id_res`,
`id_name`,
`cut_name`,
`value`,
`one_sum`,
envelope.`id` as envid,
`id_resval`,
`n_isx`,
`d_isx`,
`orders`,
envelope.`dates`
from envelope
    left join resvalue on envelope.id_resval = resvalue.id
where  DATE_FORMAT(envelope.`dates`, "%Y-%m")=DATE_FORMAT(CURDATE(), "%Y-%m") order by envelope.id desc');

$result = mysql_query($query);
    $num_rows = mysql_num_rows($result);
for($i=1; $i <= $num_rows; $i++)
{ $row = mysql_fetch_array($result);
echo '<tr>
<td>'.$i.'</td>
<td><a href="form_edit.php?id='.$row["envid"].'"><i class="fa fa-picture-o" aria-hidden="true"></i></a></td>
<td>'.date("d-m-Y", strtotime($row['dates'])).'</td>
<td>'.$row["nemark"].'</td>
<td>'.$row["cut_name"].'</td>
<td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
<td>'.$row["n_isx"].'</td>
<td>'.date("d-m-Y", strtotime($row['d_isx'])).'</td>
<td>'.($row["orders"]==1 ? 'Да' : 'Нет').'</td>
<td>';
$query1= ('select stamp.`id` as stampid, resvalue.`id` as resid, stamp.`value` as rvalue,`id_name`,`one_sum`,`cut_name`,(stamp.`value`*one_sum) as total
from stamp
  left join resvalue on resvalue.id = stamp.id_resval
   where stamp.`id_envelope`='.$row["envid"].'');
       $result1 = mysql_query($query1);
            while ( $row1 = mysql_fetch_array($result1 )){
echo '  '.$row1["rvalue"].'шт. * '.number_format($row1["one_sum"], 2, ',', ' ').' р. = '.number_format($row1["total"], 2, ',', ' ').' р.<br />';
          }
echo "</td><td $hidden>
<a href=\"del.php?id=a1:".$row["envid"]."\" onclick=\"return confirm ('Вы уверены?');\"> <i class=\"glyphicon glyphicon-trash\"></i></a>

</td></tr>";
   }
?>
</tbody></table>
                 <br /><br />
                </div>
        </span>
     </div>
</div>
<script src="themes/js/bootstrap-datepicker.min.js"></script>
<script src="themes/js/bootstrap-datepicker.ru.min.js"></script>
<script src="themes/js/jquery.maskedinput.min.js"></script>
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
location = 'index.php';
        }
    });
});
jQuery(function($) {
$('#ndates').mask('99-99-9999');
});
$('#dt').datepicker({
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        clearBtn: true,
        language: "ru",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });

 $(document).ready(function(){
    $("#link-load a").click(function () {
     $("#divLoad").load($(this)[0].href);
      return false;
    });
  });

$.fn.ready(function() {
    $(document).on('click', '.spoiler-btn', function (e) {
        e.preventDefault()
        $(this).parent().children('.spoiler-body').collapse('toggle')
    });
});
</script>
</body></html>
