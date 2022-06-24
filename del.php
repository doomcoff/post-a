<?php
include('session_start.php');  
$url = $_SERVER['HTTP_REFERER'];
$_POST["id"] = strip_data($_GET["id"]);
$e = explode(":", $_POST["id"]);

$ind=($e[0]);
$id=($e[1]);

if ($ind=='a1'){
$sql = ("select id_resval,value from stamp where id_envelope='$id'");
$dbc = mysql_query($sql);
while ($row = mysql_fetch_array($dbc)) {
$st_id = $row['id_resval'];
$st_value = $row['value'];
             $query = "update resvalue set value=value+'$st_value' where id='$st_id'";
             mysql_query($query) or die("Инфа не записана upd value.");
                        }

$sql = ("select id,id_resval from `envelope` where id='$id'");
$dbc = mysql_query($sql);
while ($row = mysql_fetch_array($dbc)) {
$en_id = $row['id'];
$en_res_id = $row['id_resval'];

$query = "DELETE from stamp WHERE id_envelope='$en_id'";
mysql_query($query) or die("Хьюстон у нас проблемы.");
         }
      $query = "DELETE from envelope WHERE id='$en_id'";
       mysql_query($query) or die("Хьюстон у нас проблемы.");
                          $query = "update resvalue set value=value+1 where id='$en_res_id'";
                           mysql_query($query) or die("Инфа не записана upd value.");
            header("Location: $url");
}

      if ($ind=='b1'){
        $query = "DELETE from resvalue WHERE id='$id'";
          mysql_query($query) or die("Хьюстон у нас проблемы (files).");
   header("Location: $url");
          }
           else {
     header("Location: $url");
 }
?>
