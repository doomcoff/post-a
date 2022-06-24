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

$sql = 'select
DATE_FORMAT(envelope.`dates`, "%d-%m-%Y") d,
CASE WHEN envelope.labeled=1 THEN "Маркированный" WHEN envelope.labeled=2 THEN "Не маркированный" WHEN envelope.labeled=3 THEN "Литера А" ELSE 0 END mark,
`id_name`,
`cut_name`,
`full_name`,
`nclv`,
ifnull(CAST(REPLACE(`nsum`, ".", ",")as CHAR(50)),0)  s,
`n_isx`,
DATE_FORMAT(d_isx, "%d-%m-%Y") disx,
CASE WHEN envelope.orders=1 THEN "Заказное" ELSE "" END zac,
ifnull(`clv`,0) mclv,
ifnull(CAST(REPLACE(sumitog, ".", ",")as CHAR(50)),0) ms,
ifnull(`concats`,"") m

from envelope
    left join resvalue on envelope.id_resval = resvalue.id
     left join stamp_e on stamp_e.`id_envelope` = envelope.id

where  DATE_FORMAT(envelope.`dates`, "%Y-%m")="'.$d.'"
order by 1
';
$res = mysql_query($sql);

echo '
  <table border ="1">
 <tr>
 <th>Дата</th>
 <th>Маркировка</th>
 <th>Наименование</th>
 <th>СокрНаименование</th>
 <th>Закуплено</th>
 <th>Цена</th>
 <th>НИсходящего</th>
 <th>ДатаИсходящего</th>
 <th>Заказное</th>
 <th>Марки_количество</th>
 <th>Марки_сумма</th>
 <th>МаркиРазбивка</th>
 </tr>
';

while ( $row = mysql_fetch_array( $res ) ){
 echo '<tr>
 <td>'.$row['d'].'</td>
 <td>'.$row['mark'].'</td>
 <td>'.$row['id_name'].'</td>
 <td>'.$row['cut_name'].'</td>

 <td>'.$row['nclv'].'</td>
 <td>'.$row['s'].'</td>
 <td>'.$row['n_isx'].'</td>
 <td>'.$row['disx'].'</td>
 <td>'.$row['zac'].'</td>
 <td>'.$row['mclv'].'</td>
 <td>'.$row['ms'].'</td>
 <td>'.$row['m'].'</td>
       </tr>';
}
echo '</table>';
echo '</body></html>';
   ?>
