<?php
function date_smart($date_input, $time=false) {
$monthes = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня','июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
$week = array('воскресенье', 'понедельник','вторник','среда','четверг','пятница','суббота');
$date = strtotime($date_input);
if($time) $time = ' G:i';
	else $time = '';

if(date('Y') == date('Y',$date)) {
		if(date('z') == date('z', $date)) {
			$result_date = date('Сегодня'.$time, $date);
		} elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)+1,date('Y',$date)))) {
			$result_date = date('Вчера'.$time, $date);
		} elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)-1,date('Y',$date)))) {
			$result_date = date('Завтра'.$time, $date);
		}
        if(isset($result_date)) return $result_date;
	}
$month = $monthes[date('n',$date)];
$weeks = $week[date('w',$date)];
if(date('Y') != date('Y', $date)) $year = 'Y г.';
	else $year = 'Y г.';
$result_date = date('j '.$month.' '.$year.' ('.$weeks.')'.$time, $date);
	return $result_date;
}

   function plural_type($n) {return ($n%10==1 && $n%100!=11 ? 0 : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2));}
   $_plural_years = array('год', 'года', 'лет');
   $_plural_months = array('месяц', 'месяца', 'месяцев');
   $_plural_days = array('день', 'дня', 'дней');
   $_plural_times = array('раз', 'раза', 'раз');
   $_plural_doc = array('документ', 'документа', 'документов');
   $_plural_row = array('запись', 'записи', 'записей');
   $_plural_stamp = array('марка', 'марки', 'марок');
   $_plural_env = array('конверт', 'конверта', 'конвертов');

function date_ru($date_input, $time=false) {
$monthes = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня','июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
$week = array('воскресенье', 'понедельник','вторник','среда','четверг','пятница','суббота');
$date = strtotime($date_input);
if($time) $time = ' G:i';
	else $time = '';

$month = $monthes[date('n',$date)];
$weeks = $week[date('w',$date)];
$year = date('Y', $date);
$result_date = date('j '.$month.' '.$year.' '.$time, $date);
	return $result_date;
}

$monthe_r = array('01'=>'январь','02'=>'февраль','03'=> 'март', '04'=> 'апрель','05'=> 'май', '06'=> 'июнь','07'=> 'июль','08'=> 'август','09'=> 'сентябрь','10'=> 'октябрь','11'=> 'ноябрь','12'=> 'декабрь');
$monthe_r1 = array ('01' => "января", '02' => "февраля", '03' => "марта", '04' => "апреля", '05' => "мая", '06' => "июня", '07' => "июля", '08' => "августа", '09' => "сентября", '10' => "октября", '11' => "ноября", '12' => "декабря");
$monthe_a1 = array (1 => "январь", 2 => "февраль", 3 => "март", 4 => "апрель", 5 => "май", 6 => "июнь", 7 => "июль", 8 => "август", 9 => "сентябрь", 10 => "октябрь", 11 => "ноябрь", 12 => "декабрь");

function empty_phrases(){
    $phrases = array(
     'Нет, — сказал Ёжик. — Меня ни капельки нет. Понимаешь?',
     'Ты только представь себе: меня нет, ты сидишь один и поговорить не с кем.',
      'А голова — предмет тёмный, исследованию не подлежит.',
      'Хороший человек… Солонку спёр… И не побрезговал.',
'Зимой можешь гадить сколько хочешь, но летом снег растает… Хаус ©'
      );

return $phrases[mt_rand(0, count($phrases)-1)];
}
?>
