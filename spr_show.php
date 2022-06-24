<?php
include('session_start.php');  
include('fun.php');
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
<link href="themes/css/jquery.dataTables.css" rel="stylesheet">
<script src="themes/js/jquery.js"></script>
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

<div class="panel panel-default m" id="mMenu">
    <div class="menucont"><br />
        <div class="btn btn-icon btn-lg"><a href="index.php">   <i class="fa fa-envelope" aria-hidden="true"></i> Форма ввода</a></div>
        <div class="btn btn-icon btn-lg"><a href="spr_show.php"><i class="glyphicon glyphicon-cog"></i>           Справочник</a></div>
        <div class="btn btn-icon btn-lg"><a href="logout.php">  <i class="glyphicon glyphicon-off"></i>           Выйти </a><span class="small text-muted">(<?php echo $_SESSION['login']; ?>)</span></div>
      </div>
   </div>

  <div class="box-content">
Приход и остатки по месяцам
    <div id="link-load">
        <ul class="list-group">

<?php
$sql = 'SELECT distinct DATE_FORMAT(dates, "%Y") as Datev from envelope';
$dbc = mysql_query($sql);
while ($row = mysql_fetch_array($dbc)) {
$y= $row['Datev'];
echo '<div class="spoiler"><div class="spoiler-btn"><h3>'.$y.' <i class="fa fa-angle-down" aria-hidden="true"></i></h3></div>';

$sql = 'SELECT distinct DATE_FORMAT(dates, "%Y-%m") as dmy from envelope where DATE_FORMAT(dates, "%Y") = '.$y.' order by dates';
    $q = mysql_query($sql);
    while ($row = mysql_fetch_array($q)) {
            $dates=($row['dmy']);
          $dm=date("m", strtotime($row['dmy']));



    echo ' <div class="spoiler-body collapse">';
         echo '<li class="list-group-item"><a href="select_month.php?my='.$dates.'">'.$monthe_r[$dm].'</a></li></div>';
   }
echo '</div>';
}
?>
</ul>
</div>
  </div>
      </div>


 <div class="col-md-10">
       <span id="divLoad">
     <div class="page-header">
           <h4><a href="Xls_resvalue.php" class="btn  btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Выгрузить в excel</a>&nbsp;&nbsp;&nbsp;Справочник <small></small></h4>
                 </div>

<div id="edit" class="collapse___">
     <div class="jumbotron"  style="border-radius: 0px; padding-top: 10px; padding-bottom: 10px;">
           <form id="addconv" class="form-horizontal" role="form" method="POST" action="add.php?add=a" onSubmit="return reviewsAdd(this);" >

<div class="form-group">
  <label for="" class="col-sm-2 control-label">ТМЦ (конверты, марки)<span class="text-danger"> *</span></label>
      <div class="col-sm-2">
        <select class="form-control" name="id_res" id="id_res" onchange="change_select(this)">
<option value="" selected="selected"></option>
<option value="1">Конверты</option>
<option value="2">Марки</option>
      </select>
      </div>
</div>


<div class="form-group">
  <label for="input" class="col-sm-2 control-label">Количество <span class="text-danger"> *</span></label>
      <div class="col-sm-2">
           <input name="value" id="value" value="" class="form-control" required />
      </div>
</div>

<div class="form-group">
   <label for="input" class="col-sm-2 control-label">Цена 1 экземпляра <span class="text-danger"> *</span></label>
        <div class="col-sm-2">
           <input name="one_sum" id="one_sum" value="" class="form-control" required />
        </div>
   </div>


<div class="form-group">

   <label for="input" class="col-sm-2 control-label">Тип <span class="text-danger"> *</span></label>
        <div class="col-sm-2">

        <select name="labeled" id="labeled1" class="form-control"  style="display:none" disabled="disabled">
<option value="0" selected="selected"></option>
<option value="1">Маркированный</option>
<option value="2">Не маркированный</option>
<option value="3">Литера А</option>
</select>

<input name="labeled" id="labeled2" value="Номинал" class="form-control" required="required" style="display:none" disabled="disabled"/>
                </div>



   <label for="input" class="col-sm-2 control-label">Краткое наименование <span class="text-danger"> *</span></label>
        <div class="col-sm-4">
           <input name="cut_name" id="cut_name" value="" class="form-control" required="required"/>
        </div>
   </div>

<div class="form-group">
   <label for="input" class="col-sm-2 control-label">Полное наименование <span class="text-danger"> *</span></label>
        <div class="col-sm-10">
           <input name="full_name" id="full_name" value="" class="form-control" required="required"/>
        </div>
   </div>
<input type="submit" class="btn btn-primary" value="Внести" name="btn">&nbsp;&nbsp;
<input type="reset" class="btn btn-default" value="Очистить форму"><span id="otv" class="text-danger"></span>
</div>
   </form>
</div>

<div id="tbs">
<table id="tblscroll" class="display table link-load table-striped" style="width:100%">
<thead>
<tr class="warning">
<th class="text-center">№</th>
<th class="text-center">ID</th>
			<th class="text-center">Наименование</th>
            <th class="text-center">Тип</th>
			<th class="text-center">Краткое имя</th>
			<th class="text-center">Полное имя</th>
			<th class="text-center">Остатки кол-во</th>
            <th class="text-center">Остатки сумм.</th>
            <th class="text-center">Цена экземпляра</th>
            <th class="text-center">Закупленно</th>
			<th class="text-center">Полная стоимость</th>
			<th class="text-center">Дата </th>
            <th class="text-center" <?php echo $hidden; ?>><i class="glyphicon glyphicon-trash"></i></th>
</tr>
</thead>
<tbody>       <!--<td><a href="spr_edit.php?id='.$row["id"].'"><i class="glyphicon glyphicon-pencil"></i></a></td>-->
<?php
$query= ('SELECT `id`,`id_res`,`id_name`,
CASE WHEN `labeled`=1 THEN "Маркированный"
      WHEN `labeled`=2 THEN "Не маркированный"
		WHEN `labeled`=3 THEN "Литера А"
ELSE "" END labeled,
`cut_name`,`full_name`,`value`,`value`*`one_sum` valsum,`value_k`,`one_sum`,`sum_k`,`datestamp`,`dates`
from resvalue order by id desc');
 $result = mysql_query($query);
    $num_rows = mysql_num_rows($result);
for($i=1; $i <= $num_rows; $i++)
{ $row = mysql_fetch_array($result);
echo '<tr>
<td>'.$i.'</td>
<td>'.$row["id"].'</td>
<td>'.$row["id_name"].'</td>
<td>'.$row["labeled"].'</td>
<td>'.$row["cut_name"].'</td>
<td>'.$row["full_name"].'</td>
<td>'.$row["value"].'</td>
<td>'.number_format($row['valsum'], 2, ',', ' ').'</td>
<td>'.number_format($row['one_sum'], 2, ',', ' ').'</td>
<td>'.$row["value_k"].'</td>
<td>'.number_format($row['sum_k'], 2, ',', ' ').'</td>
<td>'.date('d-m-Y', strtotime($row["dates"])).'</td>    
<td '.$hidden.'>';
echo "<a href=\"del.php?id=b1:".$row["id"]."\" onclick=\"return confirm ('Вы уверены?');\"> <i class=\"glyphicon glyphicon-trash\"></i></a>";
echo '</td></tr>';
}
?>
</tbody></table> <br /><br />
            </div>
        </div>
      </div>
      </span>
    </div>
  </div>
<script src="themes/js/bootstrap.min.js"></script>
<script src="themes/js/validator.js"></script>
<script src="themes/js/jquery.form.min.js"></script>
<script>
$(function(){
$('#addconv').validator({
feedback: {
success: 'glyphicon-pencil',
error: 'fa-times'
     }
   });
});

$(document).ready(function(){
  $("#tbInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tb tr").filter(function() {
      $(this).toggle( $(this).text().toLowerCase().indexOf(value) > -1 );
    });
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
 $(document).ready(function(){
    $("#link-load a").click(function () {
     $("#divLoad").load($(this)[0].href);
      return false;
    });
  });
  $(document).ready(function(){
    $(".link-loadA a").click(function () {
      $("#divLoadA").load($(this)[0].href);
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
<script type="text/javascript">
function change_select(elem) {
   switch (elem.value) {
       case '1':
                     $('#labeled2').css("display", "none");
                     $('#labeled1').css("display", "inline");
                   $('#labeled1').removeAttr('disabled');
           break;

		case '2':
               $('#labeled1').css("display", "none");
               $('#labeled2').css("display", "inline");
                   //$('#labeled2').removeAttr('disabled');
           break;
       default:
               $('#labeled1').css("display", "none");
               $('#labeled2').css("display", "none");
   }
}
</script>
</body></html>
