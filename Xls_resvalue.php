  <?php
include('session_start.php');
$_POST["m"] = $_GET["m"];
$d=$_POST['m'];

 header('Content-Type: application/vnd.ms-excel; charset=utf-8');
 header("Content-Disposition: attachment;filename=".date("d-m-Y")."-расходТМЦ.xls");
 header("Content-Transfer-Encoding: binary ");


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <meta name="author" content="" />
 </head>
 <body>';

$sql = '
select
DATE_FORMAT(`datestamp`, "%d-%m-%Y %H:%i") d,
`id_name`,
CASE WHEN labeled=1 THEN "Маркированный" WHEN labeled=2 THEN "Не маркированный" WHEN labeled=3 THEN "Литера А" ELSE 0 END mr,
`cut_name`,
`full_name`,
`value`,
`value_k`,
ifnull(CAST(REPLACE(`one_sum`, ".", ",")as CHAR(50)),0)  sc,
ifnull(CAST(REPLACE(`sum_k`, ".", ",")as CHAR(50)),0)  st
from resvalue
order by 1,5
';
$res = mysql_query($sql);

echo '
  <table border ="1">
 <tr>
 <th>Дата внесения</th>
 <th>Вид</th>
 <th>Маркировка</th>
 <th>Наименование</th>
 <th>Полное наименование</th>
 <th>Остатки</th>
 <th>Закуплено</th>
 <th>Цена</th>
 <th>Стоимость</th>
 </tr>
';

while ( $row = mysql_fetch_array( $res ) ){
 echo '<tr>
 <td>'.$row['d'].'</td>
 <td>'.$row['id_name'].'</td>
 <td>'.$row['mr'].'</td>
 <td>'.$row['cut_name'].'</td>
 <td>'.$row['full_name'].'</td>
 <td>'.$row['value'].'</td>
 <td>'.$row['value_k'].'</td>
 <td>'.$row['sc'].'</td>
 <td>'.$row['st'].'</td>
       </tr>';
}
echo '</table>';
echo '</body></html>';
   ?>
