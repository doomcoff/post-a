<?php
include('session_start.php');  
include('fun.php');
$url = $_SERVER['HTTP_REFERER'];
header("Content-type: application/vnd.ms-word");
header ( "Content-Disposition: inline; filename=envelope.rtf");
?>
<style type="text/css" media="print">

@page{
     size: A4 landscape;
      margin-left:0.5cm;
      margin-right:0.5cm;
      margin-top:0.5cm;
      filter: progid : DXImageTransform.Microsoft.BasicImage ( Rotation = 3 );
  }
table {
	border-collapse: collapse;
	margin: 5px;
 padding: 4px;
}
td.nobr {
border: 0;
text-align: center
}
td.text-right{
text-align: right
}
td.text-center{
text-align: center;
font-weight: bold
}
.font{
font-size:12px ;
}
</style>


<table border="0" style="width:100%">
<tr>
<td style="width:60%">&nbsp;</td>
<td>
Утверждаю<br />
Директор ГОКУ "ЦСПН"<br /> по Печенгскому району<br />______________Савицкая Л. Э.</td>
<tr>
</table>




<?php
//echo '<center>Отчет по почтовым расходам с 01.'.date("m.Y").'г по '.date("d.m.Y").'г</center><br />';
$_POST['id'] = $_GET['id'];
if ( isset($_POST['id'])) {
    $d1=$_POST['id'];


echo '<center><b>Отчет по почтовым расходам за '.$monthe_r[date("m", strtotime($d1))].' '.date("Y").' года</b></center><br />';
echo '<table border="1" align="center"  class="font" style="width:90%">
<tr>
        <td class="nobr" colspan="5"><b>Остатки на начало '.$monthe_r1[date("m", strtotime($d1))].'</b></td>
</tr>
<tr>
        <td class="text-center">Наименов</td>
        <td class="text-center">Полное имя</td>
        <td class="text-center">Цена</td>
        <td class="text-center">Количество</td>
        <td class="text-center">Сумма</td>
</tr>';

$query=('select
resvalue.`cut_name`,
resvalue.`full_name`,
resvalue.`one_sum`,
(resvalue.`value_k` - count(t.`id`)) clv,
(resvalue.`sum_k` - sum(ifnull(t.`nsum`,0))) summ
from (resvalue left join
(select
envelope.`id_resval`,
envelope.`nsum`,
envelope.`id`
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") < "'.$d1.'")
t on((t.`id_resval` = resvalue.`id`)))
where date_format(resvalue.`dates`,_utf8"%Y-%m") < "'.$d1.'"
and (resvalue.`id_res` = 1)
group by resvalue.`id` having clv<>0 order by 1');
                    $result = mysql_query($query);
                         while( $row = mysql_fetch_array($result)) {
$scl = 0;  $cl[] = $row['clv'];
foreach($cl as $a=>$b)
{ if(is_numeric($b))
    {
    $scl = $scl + $b;
    }
}

$ssu = 0;  $sum[] = $row['summ'];
foreach($sum as $a=>$b)
{
    if(is_numeric($b))
    {
       $ssu = $ssu + $b;
    }
}

                     echo  '<tr>
                    <td>'.$row['cut_name'].'</td>
                    <td>'.$row['full_name'].'</td>
                    <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
                    <td>'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
                     </tr>';
                            }

                     echo  '<tr>
                     <td class="text-right" colspan="3">Конверты</td>
                     <td><b>'.number_format($scl, 0, '', ' ').'</b></td>   
                     <td><b>'.number_format($ssu, 2, ',', ' ').' р.</b></td>
                     </tr>';

//марки
                      $query=('select
resvalue.`cut_name`,
resvalue.`full_name`,
resvalue.`one_sum`,
(ifnull(resvalue.`value_k`,0) - sum(ifnull(t.`value`,0))) AS `clv`,
(ifnull(resvalue.`sum_k`,0) - sum((ifnull(t.`nominal`,0) * ifnull(t.`value`,0)))) AS `summ`
from resvalue left join
(select stamp.`id_resval`,stamp.`value`,stamp.`nominal`,stamp.`id` from stamp
where date_format(stamp.`dates`,_utf8"%Y-%m")<"'.$d1.'"
group by stamp.`id`) t
     on t.`id_resval` = resvalue.`id`
where date_format(resvalue.`dates`,_utf8"%Y-%m")<"'.$d1.'"
and resvalue.`id_res`= 2
group by resvalue.`id` having clv<>0 order by 1');
                    $result = mysql_query($query);
                         while( $row = mysql_fetch_array($result)) {

$scl1 = 0;  $cl1[] = $row['clv'];
foreach($cl1 as $a1=>$b1)
{ if(is_numeric($b1))
    {
    $scl1 = $scl1 + $b1;
    }
}

$ssu1 = 0;  $sum1[] = $row['summ'];
foreach($sum1 as $a1=>$b1)
{
    if(is_numeric($b1))
    {
       $ssu1 = $ssu1 + $b1;
    }
}
$sum_clv=$scl1+$scl; $sum_sum=$ssu1+$ssu;

                     echo  '<tr>
                    <td>'.$row['cut_name'].'</td>
                    <td>'.$row['full_name'].'</td>
                    <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
                    <td>'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
                     </tr>';
                            }

                     echo  '<tr class="danger">
                     <td class="text-right" colspan="3">Марки</td>
                     <td><b>'.number_format($scl1, 0, '', ' ')  .'</b></td>
                     <td><b>'.number_format($ssu1, 2, ',', ' ').' р.</b></td>
                     </tr>';

echo  '<tr>
<td class="text-right" colspan="3"><b>Итого</b></td>
<td><b>'.number_format($sum_clv, 0, '', ' ') .'</b></td>
<td style="white-space: nowrap"><b>'.number_format($sum_sum, 2, ',', ' ').' р.</b></td>
</tr>

<tr><td class="nobr" colspan="5"><b>Приход на '.$monthe_r[date("m", strtotime($d1))].'</b></td></tr>
<tr>
        <td class="text-center">Наименов</td>
        <td class="text-center">Полное имя</td>
        <td class="text-center">Цена</td>
        <td class="text-center">Количество</td>
        <td class="text-center">Сумма</td>
</tr>';




//ОСТАТОК НА НАЧАЛО МЕС
   //ПРИХОД--------------------------------
     //приход конверты
      $query=('select
      DATE_FORMAT(`dates`, "%m") dd,
      CASE WHEN `labeled`=1 THEN "Маркированный"
      WHEN `labeled`=2 THEN "Не маркированный"
		WHEN `labeled`=3 THEN "Литера А"
      ELSE "" END labeled,
      `cut_name`,`one_sum`,
      `full_name`,
       sum(value_k) clv,
       sum(sum_k) summ
        from resvalue
      where DATE_FORMAT(dates, "%Y-%m")="'.$d1.'" and `id_res`=1
       group by  `cut_name`  order by 1');
      $result = mysql_query($query);
           while( $row = mysql_fetch_array($result)) {
      echo  '<tr>
      <td>'.$row['cut_name'].'</td>
      <td>'.$row['full_name'].'</td>
      <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
      <td>'.number_format($row['clv'], 0, '', ' ').'</td>
      <td>'.number_format($row['summ'], 2, '.', ' ').' р.</td>
      </tr>';
            }
              //приход конверты суммарно
      $query=('select
      DATE_FORMAT(`dates`, "%m") dd,
      sum(value_k) clv,
      sum(sum_k) summ
      from resvalue
      where DATE_FORMAT(dates, "%Y-%m")="'.$d1.'"  and `id_res`=1
      group by DATE_FORMAT(dates, "%Y-%m")');
      $result = mysql_query($query);
           while( $row = mysql_fetch_array($result)) {
      echo  '<tr>
      <td class="text-right" colspan="3">Конверты</td>
      <td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
      <td><b>'.number_format($row['summ'], 2, ',', ' ').' р.</b></td>
      </tr>';
            }
            //приход марки
      $query=('select
      DATE_FORMAT(`dates`, "%m") dd,
      CASE WHEN `labeled`=0 THEN "Номинал" ELSE "Ошибка" END labeled,
      `cut_name`,
      `full_name`,
       `one_sum`,
       sum(value_k) clv,
       sum(sum_k) summ
        from resvalue
      where DATE_FORMAT(dates, "%Y-%m")="'.$d1.'" and `id_res`=2
       group by  `cut_name`');
      $result = mysql_query($query);
           while( $row = mysql_fetch_array($result)) {
      echo  '<tr>
      <td>'.$row['cut_name'].'</td>
      <td>'.$row['full_name'].'</td>
      <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
      <td>'.number_format($row['clv'], 0, '', ' ').'</td>
      <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
      </tr>';
            }
               //приход марки суммарно
      $query=('select
      DATE_FORMAT(`dates`, "%m") dd,
      sum(value_k) clv,
      sum(sum_k) summ
      from resvalue
      where DATE_FORMAT(dates, "%Y-%m")="'.$d1.'"  and `id_res`=2
      group by DATE_FORMAT(dates, "%Y-%m")');
      $result = mysql_query($query);
           while( $row = mysql_fetch_array($result)) {
      echo  '<tr>
      <td class="text-right" colspan="3">Марки</td>
      <td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
      <td><b>'.number_format($row['summ'], 2, '.', ' ').' р.</b></td>
      </tr>';
            }
$query=('select
DATE_FORMAT(`dates`, "%m") dd,
ifnull(sum(value_k),0) clv,
ifnull(sum(sum_k),0) summ
from resvalue
where DATE_FORMAT(dates, "%Y-%m")="'.$d1.'"
group by DATE_FORMAT(dates, "%Y-%m")');
$result = mysql_query($query);
        while( $row = mysql_fetch_array($result)) {
echo  '<tr>
<td class="text-right" colspan="3"><b>Итого</b></td>
<td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
<td style="white-space: nowrap"><b>'.number_format($row['summ'], 2, ',', ' ').' р.</b></td>
</tr>';
    }

echo '<tr><td class="nobr" colspan="5"><b>Расход на '.$monthe_r[date("m", strtotime($d1))].'</b></td></tr>
<tr>
        <td class="text-center">Наименов</td>
        <td class="text-center">Полное имя</td>
        <td class="text-center">Цена</td>
        <td class="text-center">Количество</td>
        <td class="text-center">Сумма</td>
</tr>';
//ПРИХОД--------------------------------
       //РАСХОД-------------------------------
$query=('
select resvalue.`cut_name` AS `cut_name`,
resvalue.`full_name` AS `full_name`,
resvalue.`one_sum` AS `one_sum`,
count(envelope.`id`) AS `clv`,
sum(envelope.`nsum`) AS `summ`
from resvalue left join `posta`.`envelope`
on resvalue.`id` = envelope.`id_resval`
where date_format(envelope.`dates`,_utf8"%Y-%m") = "'.$d1.'"
and resvalue.`id_res` = 1 group by `posta`.`envelope`.`id_resval`
');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {

echo  '<tr>
<td>'.$row['cut_name'].'</td>
 <td>'.$row['full_name'].'</td>
 <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
 <td>'.number_format($row['clv'], 0, '', ' ').'</td>
 <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
 </tr>';
    }
//конверты сумма
$query=('select
sum(envelope.`one`) clv,
sum(envelope.`nsum`) summ
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {

echo  '<tr>
 <td class="text-right" colspan="3">Конверты</td>
 <td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
 <td><b>'.number_format($row['summ'], 2, ',', ' ').' р.</b></td>
 </tr>';
    }
//марки
$query=('
select resvalue.`cut_name`,
resvalue.`full_name`,
resvalue.`one_sum`,
sum(stamp.`value`)`clv`,
sum(stamp.`value` * stamp.`nominal`) `summ`
from resvalue left join stamp
on resvalue.`id` = stamp.`id_resval`
where date_format(stamp.`dates`,_utf8"%Y-%m") = "'.$d1.'"
and (resvalue.`id_res` = 2) group by stamp.`id_resval`
');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {

echo  '<tr>
<td>'.$row['cut_name'].'</td>
 <td>'.$row['full_name'].'</td>
 <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
 <td>'.number_format($row['clv'], 0, '', ' ').'</td>
 <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
 </tr>';
    }
//марки сумма
$query=('select sum(stamp.`value`)`clv`,
sum(stamp.`value` * stamp.`nominal`) `summ`
from stamp
where date_format(stamp.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {
echo'<tr>
 <td class="text-right" colspan="3">Марки</td>
 <td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
 <td><b>'.number_format($row['summ'], 2, '.', ' ').' р.</b></td>
 </tr>';
    }
$query=('select count(envelope.`id`)+
(select sum(stamp.`value`) from stamp where date_format(stamp.`dates`,_utf8"%Y-%m") = "'.$d1.'") clv,
sum(envelope.`nsum`)+(select sum(stamp.`value` * stamp.`nominal`) from stamp where date_format(stamp.`dates`,_utf8"%Y-%m") = "'.$d1.'") summ
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
$result = mysql_query($query);
        while( $row = mysql_fetch_array($result)) {
echo'<tr>
<td class="text-right" colspan="3"><b>Итого</b></td>
<td><b>'.number_format($row['clv'], 0, '', ' ').'</b></td>
<td style="white-space: nowrap"><b>'.number_format($row['summ'], 2, ',', ' ').' р.</b></td>
</tr>';
            }

echo'<tr><td class="nobr" colspan="5"><b>Остатки на конец '.$monthe_r1[date("m", strtotime($d1))].'</b></td></tr>
<tr>
        <td class="text-center">Наименов</td>
        <td class="text-center">Полное имя</td>
        <td class="text-center">Цена</td>
        <td class="text-center">Количество</td>
        <td class="text-center">Сумма</td>
</tr>';


//РАСХОД------------------------------------------
         //ОСТАТКИ НА КОНЕЦ--------------------------------------------
$query=('select
resvalue.`cut_name`,
resvalue.`full_name`,
resvalue.`one_sum`,
(resvalue.`value_k` - count(t.`id`)) clv,
(resvalue.`sum_k` - ifnull(sum(t.`nsum`),0)) summ
from (resvalue left join
(select
envelope.`id_resval`,
envelope.`nsum`,
envelope.`id`
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") <="'.$d1.'")
t on((t.`id_resval` = resvalue.`id`)))
where date_format(resvalue.`dates`,_utf8"%Y-%m") <= "'.$d1.'"
and (resvalue.`id_res` = 1)
group by resvalue.`id` having clv<>0 order by 1');
                    $result = mysql_query($query);
                         while( $row = mysql_fetch_array($result)) {

$sclv = 0;  $clv[] = $row['clv'];
foreach($clv as $a=>$b)
{ if(is_numeric($b))
    {
    $sclv = $sclv + $b;
    }
}

$ssum = 0;  $summ[] = $row['summ'];
foreach($summ as $a=>$b)
{
    if(is_numeric($b))
    {
       $ssum = $ssum + $b;
    }
}

                     echo  '<tr>
                    <td>'.$row['cut_name'].'</td>
                    <td>'.$row['full_name'].'</td>
                    <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
                    <td>'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
                     </tr>';
                            }
                     echo  '<tr>
                     <td class="text-right" colspan="3">Конверты</td>
                     <td><b>'.number_format($sclv, 0, '', ' ') .'</b></td>
                     <td><b>'.number_format($ssum, 2, ',', ' ').' р.</b></td>
                     </tr>';

//марки
                      $query=('select
resvalue.`cut_name`,
resvalue.`full_name`,
resvalue.`one_sum`,
(ifnull(resvalue.`value_k`,0) - sum(ifnull(t.`value`,0))) AS `clv`,
(ifnull(resvalue.`sum_k`,0) - sum((ifnull(t.`nominal`,0) * ifnull(t.`value`,0)))) AS `summ`
from resvalue left join
(select stamp.`id_resval`,stamp.`value`,stamp.`nominal`,stamp.`id` from stamp
where date_format(stamp.`dates`,_utf8"%Y-%m")<="'.$d1.'"
group by stamp.`id`) t
     on t.`id_resval` = resvalue.`id`
where date_format(resvalue.`dates`,_utf8"%Y-%m")<="'.$d1.'"
and resvalue.`id_res`= 2
group by resvalue.`id` having clv<>0 order by 1');
                    $result = mysql_query($query);
                         while( $row = mysql_fetch_array($result)) {
$sclv1 = 0;  $clv1[] = $row['clv'];
foreach($clv1 as $a1=>$b1)
{ if(is_numeric($b1))
    {
    $sclv1 = $sclv1 + $b1;
    }
}

$ssum1 = 0;  $summ1[] = $row['summ'];
foreach($summ1 as $a1=>$b1)
{
    if(is_numeric($b1))
    {
       $ssum1 = $ssum1 + $b1;
    }
}

$sum_clv1=$sclv1+$sclv; $sum_sum1=$ssum1+$ssum;
                     echo  '<tr>
                    <td>'.$row['cut_name'].'</td>
                    <td>'.$row['full_name'].'</td>
                    <td>'.number_format($row["one_sum"], 2, ',', ' ').'</td>
                    <td>'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td>'.number_format($row['summ'], 2, ',', ' ').' р.</td>
                     </tr>';
                            }

          echo  '<tr>
                     <td class="text-right" colspan="3">Марки</td>
                     <td><b>'.number_format($sclv1, 0, '', ' ') .'</b></td>
                     <td><b>'.number_format($ssum1, 2, ',', ' ').' р.</b></td>
                     </tr>';

echo  '<tr>
<td class="text-right" colspan="3"><b>Итого</b></td>
<td><b>'.number_format($sum_clv1, 0, '', ' ') .'</b></td>
<td style="white-space: nowrap"><b>'.number_format($sum_sum1, 2, ',', ' ').' р.</b></td>
</tr>';

 echo '</table>';
//$f = explode(' ', $_SESSION['fio']);  echo substr($f[1],0,2).'. '.substr($f[2],0,2).'. '.$f['0'].''; 
}
?>
<p>Делопроизводитель ________________ Н.В. Плетнева</p>




