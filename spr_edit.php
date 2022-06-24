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
<?php
if ( is_numeric($_GET['id']) ) {
$id = mysql_real_escape_string(trim($_GET['id']));

$sql = ('SELECT * from resvalue where id="'.$id.'"');
$result = mysql_query( $sql);
$row = mysql_fetch_array($result);
?>

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
     </div>

 <div class="col-md-10">
        <div class="page-header">
      <h4>Редактировать справочник<small> <?php echo '('.$row['id_name'].' ID '.$row['id'].' от '.date("d-m-Y", strtotime($row["dates"])).')'?></small></h4>
</div>

 <div class="jumbotron">

<form id="addconv" class="form-horizontal" role="form" method="POST" action="upd.php?add=a" onSubmit="return reviewsAdd(this);" >

<div class="form-group">
  <label for="" class="col-sm-2 control-label">ТМЦ (конверты, марки)<span class="text-danger"> *</span></label>
      <div class="col-sm-2">
             <span><?php echo ''.$row['id_name'].'' ?></span>
             <input name="id" type="hidden" value="<?php echo ''.$row['id'].'' ?>">
      </div>
</div>


<div class="form-group">
  <label for="input" class="col-sm-2 control-label">Количество <span class="text-danger"> *</span></label>
      <div class="col-sm-2">
           <input name="value" id="value" value="<?php echo ''.$row['value'].'' ?>" class="form-control" required />
      </div>
</div>

<div class="form-group">
   <label for="inputEmail3" class="col-sm-2 control-label">Стоимость 1 экземпляра <span class="text-danger"> *</span></label>
        <div class="col-sm-2">
           <input name="one_sum" id="one_sum" value="<?php echo ''.$row['one_sum'].'' ?>" class="form-control" required />
        </div>
   </div>


<div class="form-group">
   <label for="inputEmail3" class="col-sm-2 control-label">Краткое наименование <span class="text-danger"> *</span></label>
        <div class="col-sm-6">
           <input name="cut_name" id="cut_name" value="<?php echo ''.$row['cut_name'].'' ?>" class="form-control" required="required"/>
        </div>
   </div>

<div class="form-group">
   <label for="inputEmail3" class="col-sm-2 control-label">Полное наименование <span class="text-danger"> *</span></label>
        <div class="col-sm-10">
           <input name="full_name" id="full_name" value="<?php echo ''.$row['full_name'].'' ?>" class="form-control" required="required"/>
        </div>
   </div>
   <input type="submit" class="btn btn-primary" value="Изменить" name="btn">&nbsp;&nbsp;<span id="otv" class="text-danger"></span>
    <input type="submit" class="btn btn-default" value="Назад" onclick="javascript:history.back(); return false;">

</div>
   </form>
           </div>
        </div>
      </div>
    </div>
  </div>
<?php } else {echo 'подмена id';}  ?>
<script>
$(document).ready(function() {
     $('#addconv').ajaxForm({
         target: '#otv',
        success: function() {
       $('#otv').fadeIn('slow');
        }
    });
});

$(function() {
  $('#mMenu').click(function() {
    $('.menucont').slideToggle();
  });
});
</script>
</body></html>
