<?php
include('session_start.php');  
include('fun.php');
$url = $_SERVER['HTTP_REFERER'];

$_POST["m"] = $_GET["m"];
if ( isset($_POST["m"]) ) {
    $d=$_POST['m'];
echo '<br />
<center><a href="Xls.php?m='.$d.'" class="btn btn-info btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Выгрузить в excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Данные за '.$monthe_r[date("m", strtotime($d))].' '.date('Y').'</center><br />';

$sql = "select
sum(`orders`) orders,
sum(CASE WHEN labeled=2 THEN 1 ELSE 0 END) nemark,
sum(CASE WHEN labeled=1 THEN 1 ELSE 0 END) mark,
count(envelope.id) clv,
sum(envelope.`nsum`) sumenv,
sum(stamp_e.clv) clvm,
sum(stamp_e.`sumitog`) summ
from envelope
left join stamp_e
    ON envelope.id = stamp_e.id_envelope
where  DATE_FORMAT(`dates`,'%Y-%m')='".$d."'
group by DATE_FORMAT(`dates`,'%m')
";
$db = mysql_query($sql);
$rows = mysql_fetch_array($db);
    if (empty($rows)) { echo 'Тут пустота, тут лоси бродят...';}
         else{
echo 'Итого <b>'.$rows['clv'].'</b> '.$_plural_env[plural_type($rows['clv'])].' на сумму <b>'.number_format($rows['sumenv'], 2, ',', ' ').' р.</b> маркерованных <b>'.$rows['mark'].' шт.</b>
 немаркерованных <b>'.$rows['nemark'].' шт.</b>
 заказных <b>'.$rows['orders'].' шт.</b>
 и <b>'.$rows['clvm'].'</b> '.$_plural_stamp[plural_type($rows['clvm'])].' на сумму <b>'.number_format($rows['summ'], 2, ',', ' ').' р.</b>';
}


echo '<hr size="1" />
<table id="tblscroll1" class="display table table-striped link-load" style="width:100%">
<thead>
<tr class="warning">
<th>№</th>
<th><i class="fa fa-picture-o" aria-hidden="true"></i></th>
<th>Дата</th>
			<th>Маркировка</th>
			<th>Сокращенное наименование</th>
			<th>Цена конверта</th>
			<th>Исх №</th>
            <th>Исх дата</th>
            <th>Заказной</th>
			<th>Марки</th>
            <th '.$hidden.'><i class="glyphicon glyphicon-trash"></i></th>
</tr>
</thead>
<tbody> ';


$query= ('SELECT resvalue.`id` as resid,
CASE WHEN envelope.labeled=1 THEN "Да" WHEN envelope.labeled=2 THEN "Нет" WHEN envelope.labeled=3 THEN "А" ELSE 0 END nemark,
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
where  DATE_FORMAT(envelope.`dates`, "%Y-%m")="'.$d.'"');

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
echo '  '.$row1["rvalue"].'шт. * '.number_format($row1["one_sum"], 2, '.', ' ').' р. = '.number_format($row1["total"], 2, '.', ' ').' р.<br />';
          }
echo "</td><td $hidden>
<a href=\"del.php?id=a1:".$row["envid"]."\" onclick=\"return confirm ('Вы уверены?');\"> <i class=\"glyphicon glyphicon-trash\"></i></a>

</td></tr>";
   }




echo '</tbody></table>';
}




//--------------AAAAAAAAA

$_POST["my"] = $_GET["my"];
if ( isset($_POST['my'])) {
    $d1=$_POST['my'];

echo '<br /><center><a href="print_word.php?id='.$d1.'" class="btn btn-info btn-sm"><i class="fa fa-file-word-o" aria-hidden="true"></i> Выгрузить в word</a>&nbsp;&nbsp;&nbsp;
Данные за '.$monthe_r[date("m", strtotime($d1))].' '.date('Y').'</center><br />';





echo '<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0" id="tb">

<tr><td class="nobr text-center" colspan="5"><b>Остаток на начало '.$monthe_r1[date("m", strtotime($d1))].'</b></td></tr>
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

      //and (resvalue.`id_res` = 1) and resvalue.value>0


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

                     echo  '<tr class="danger">
                    <td class="text-center">'.$row['cut_name'].'</td>
                    <td class="text-center">'.$row['full_name'].'</td>
                    <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td> 
                    <td class="text-center">'.number_format($row['clv'], 0, '', ' ') .'</td>
                    <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
                     </tr>';
                            }

                     echo  '<tr class="danger">
                     <td class="text-right" colspan="3"><b>Конверты</b></td>
                     <td class="text-center"><b>'.number_format($scl, 0, '', ' ').'</b></td>
                     <td class="text-center"><b>'.number_format($ssu, 2, ',', ' ').'р.</b></td>
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

                     echo  '<tr class="danger">
                    <td class="text-center">'.$row['cut_name'].'</td>
                    <td class="text-center">'.$row['full_name'].'</td>
                    <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
                    <td class="text-center">'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
                     </tr>';
                            }

                     echo  '<tr class="danger">
                     <td class="text-right" colspan="3"><b>Марки</b></td>
                     <td class="text-center"><b>'.number_format($scl1, 0, '', ' ') .'</b></td>
                     <td class="text-center"><b>'.number_format($ssu1, 2, ',', ' ').'р.</b></td>
                     </tr>';

echo  '<tr class="danger">
<td class="text-right" colspan="3"><b>Итого</b></td>
<td class="text-center"><b>'.number_format($sum_clv, 0, '', ' ').'</b></td>
<td class="text-center" style="white-space: nowrap"><b>'.number_format($sum_sum, 2, ',', ' ').'р.</b></td>
</tr>

<tr><td class="nobr text-center" colspan="5"><b>Приход на '.$monthe_r[date("m", strtotime($d1))].'</b></td></tr>
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
       group by  `cut_name`');
      $result = mysql_query($query);
           while( $row = mysql_fetch_array($result)) {
      echo  '<tr class="warning">
      <td class="text-center">'.$row['cut_name'].'</td>
      <td class="text-center">'.$row['full_name'].'</td>
      <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
      <td class="text-center">'.number_format($row['clv'], 0, '', ' ') .'</td>
      <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
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
      echo  '<tr class="warning">
      <td class="text-right" colspan="3"><b>Конверты</b></td>
      <td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
      <td class="text-center"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
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
      echo  '<tr class="warning">
      <td class="text-center">'.$row['cut_name'].'</td>
      <td class="text-center">'.$row['full_name'].'</td>
      <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
      <td class="text-center">'.number_format($row['clv'], 0, '', ' ').'</td>
      <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
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
      echo  '<tr class="warning">
      <td class="text-right" colspan="3"><b>Марки</b></td>
      <td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
      <td class="text-center"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
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
echo  '<tr class="warning">
<td class="text-right" colspan="3"><b>Итого</b></td>
<td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
<td class="text-center" style="white-space: nowrap"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
</tr>';
    }

echo'<tr><td class="nobr text-center" colspan="5"><b>Расход за '.$monthe_r[date("m", strtotime($d1))].'</b></td></tr>
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

echo  '<tr class="info">
<td class="text-center">'.$row['cut_name'].'</td>
 <td class="text-center">'.$row['full_name'].'</td>
<td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
 <td class="text-center">'.number_format($row['clv'], 0, '', ' ') .'</td>
 <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
 </tr>';
    }
//конверты сумма
$query=('select
ifnull(count(envelope.`id`),0) clv,
sum(envelope.`nsum`) summ
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {

echo  '<tr class="info">
 <td class="text-right" colspan="3"><b>Конверты</b></td>
 <td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
 <td class="text-center"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
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

echo  '<tr class="info">
<td class="text-center">'.$row['cut_name'].'</td>
 <td class="text-center">'.$row['full_name'].'</td>
<td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
 <td class="text-center">'.number_format($row['clv'], 0, '', ' ') .'</td>
 <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
 </tr>';
    }
//марки сумма
$query=('select ifnull(sum(stamp.`value`),0) `clv`,
sum(stamp.`value` * stamp.`nominal`) `summ`
from stamp
where date_format(stamp.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
 $result = mysql_query($query);
       while( $row = mysql_fetch_array($result)) {
echo  '<tr class="info">
 <td class="text-right" colspan="3"><b>Марки</b></td>
 <td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
 <td class="text-center"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
 </tr>';
    }
$query=('select ifnull(count(envelope.`id`),0)+
(select ifnull(sum(stamp.`value`),0) from stamp where date_format(stamp.`dates`,_utf8"%Y-%m")="'.$d1.'") clv,
sum(envelope.`nsum`)+(select sum(stamp.`value` * stamp.`nominal`) from stamp where date_format(stamp.`dates`,_utf8"%Y-%m")="'.$d1.'") summ
from envelope
where date_format(envelope.`dates`,_utf8"%Y-%m") = "'.$d1.'"');
$result = mysql_query($query);
        while( $row = mysql_fetch_array($result)) {
echo  '<tr class="info">
<td class="text-right" colspan="3"><b>Итого</b></td>
<td class="text-center"><b>'.number_format($row['clv'], 0, '', ' ') .'</b></td>
<td class="text-center" style="white-space: nowrap"><b>'.number_format($row['summ'], 2, ',', ' ').'р.</b></td>
</tr>';
            }


echo'<tr><td class="nobr text-center" colspan="5"><b>Остаток на конец '.$monthe_r1[date("m", strtotime($d1))].'</b></td></tr>
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
                  //and (resvalue.`id_res` = 1) and resvalue.value>0

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

                     echo  '<tr class="danger">
                    <td class="text-center">'.$row['cut_name'].'</td>
                    <td class="text-center">'.$row['full_name'].'</td>
                    <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
                    <td class="text-center">'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
                     </tr>';
                            }
                     echo  '<tr class="danger">
                     <td class="text-right" colspan="3"><b>Конверты</b></td>
                     <td class="text-center"><b>'.number_format($sclv, 0, '', ' ').'</b></td>
                     <td class="text-center"><b>'.number_format($ssum, 2, ',', ' ').'р.</b></td>
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
                     echo  '<tr class="danger">
                    <td class="text-center">'.$row['cut_name'].'</td>
                    <td class="text-center">'.$row['full_name'].'</td>
                    <td class="text-center">'.number_format($row['one_sum'], 2, ',', ' ').'</td>
                    <td class="text-center">'.number_format($row['clv'], 0, '', ' ').'</td>
                    <td class="text-center">'.number_format($row['summ'], 2, ',', ' ').'р.</td>
                     </tr>';
                            }

          echo  '<tr class="danger">
                     <td class="text-right" colspan="3"><b>Марки</b></td>
                     <td class="text-center"><b>'.number_format($sclv1, 0, '', ' ').'</b></td>
                     <td class="text-center"><b>'.number_format($ssum1, 2, ',', ' ').'р.</b></td>
                     </tr>';

echo  '<tr class="danger">
<td class="text-right" colspan="3"><b>Итого</b></td>
<td class="text-center"><b>'.number_format($sum_clv1, 0, '', ' ') .'</b></td>
<td class="text-center" style="white-space: nowrap"><b>'.number_format($sum_sum1, 2, ',', ' ').'р.</b></td>
</tr>';

 echo '</table>';

}

?>
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
    $('#tblscroll1').DataTable( {

            dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'print'
        ],
        deferRender:    true,
        scrollY:        650,
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
