<?php
include('session_start.php');

$_POST["m"] = $_GET["m"];
if ( isset($_POST["m"]) ) {
      $d=$_POST['m'];

$sql = '
SELECT
DATE_FORMAT(envelope.`dates`, "%d-%m-%Y") Дата,
CASE WHEN envelope.labeled=1 THEN "Маркированный" WHEN envelope.labeled=2 THEN "Не маркированный" WHEN envelope.labeled=3 THEN "Литера А" ELSE 0 END Маркировка,
`id_name`,
`cut_name` СокрНаименование,
`full_name` ПолноеНименование,
`nclv` Закуплено,
ifnull(CAST(REPLACE(`nsum`, ".", ",")as CHAR(50)),0)  Цена,
`n_isx` НИсходящего,
DATE_FORMAT(d_isx, "%d-%m-%Y") ДатаИсходящего,
CASE WHEN envelope.orders=1 THEN "Заказное" ELSE "" END Заказное,
ifnull(`clv`,0) Марки_количество,
ifnull(CAST(REPLACE(sumitog, ".", ",")as CHAR(50)),0) Марки_сумма,
ifnull(`concats`,"") МаркиРазбивка

from envelope
    left join resvalue on envelope.id_resval = resvalue.id
     left join stamp_e on stamp_e.`id_envelope` = envelope.id

where  DATE_FORMAT(envelope.`dates`, "%Y-%m")="'.$d.'"
order by 1

';
}
$Connect = @mysql_connect($dbhost, $dbuser, $dbpass)
    or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
//select database
$Db = @mysql_select_db($dbname, $Connect)
    or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());
//execute query
$result = @mysql_query($sql,$Connect)
    or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());

if (isset($w) && ($w==1)){
$file_type = "msword";
$file_ending = "doc";
}
else {
$file_type = "vnd.ms-excel";
$file_ending = "xls";
}

header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=database_dump.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");


if (isset($w) && ($w==1)) //check for $w again

{

if ($Use_Title == 1){
echo("$title\n\n");
}
//define separator (defines columns in excel & tabs in word)
$sep = "\n"; //new line character

    while($row = mysql_fetch_row($result))
    {
        //set_time_limit(60); // HaRa
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
        //define field names
        $field_name = mysql_field_name($result,$j);
        //will show name of fields
        $schema_insert .= "$field_name:\t";
            if(!isset($row[$j])) {
                $schema_insert .= "NULL".$sep;
                }
            elseif ($row[$j] != "") {
                $schema_insert .= "$row[$j]".$sep;
                }
            else {
                $schema_insert .= "".$sep;
                }
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        //end of each mysql row
        //creates line to separate data from each MySQL table row
        print "\n----------------------------------------------------\n";
}
}
else
/*    FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
{
//create title with timestamp:
if ($Use_Title == 1){
echo("$title\n");
}
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");
//end of printing column names

//start while loop to get data
/*
note: the following while-loop was taken from phpMyAdmin 2.1.0.
--from the file "lib.inc.php".
*/
    while($row = mysql_fetch_row($result))
    {
        //set_time_limit(60); // HaRa
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
		//following fix suggested by Josue (thanks, Josue!)
		//this corrects output in excel when table fields contain \n or \r
		//these two characters are now replaced with a space
		$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
}
?>
