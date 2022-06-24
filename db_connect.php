<?php
$dbhost = "127.0.0.1";
$dbname = "posta";
$dbuser = "root";
$dbpass = "root";
$tables = "*";

$connect = mysql_connect($dbhost, $dbuser, $dbpass) or die('cannot connect to db');
mysql_select_db($dbname) or die('cannot connect to db');
mysql_query("SET NAMES 'utf8'", $connect);

function strip_data($text)
{
    $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!" );
    $goodquotes = array ("-", "+", "#" );
    $repquotes = array ("\-", "\+", "\#" );
    $text = trim( strip_tags( $text ) );
    $text = str_replace( $quotes, '', $text );
    $text = str_replace( $goodquotes, $repquotes, $text );
    $text = ereg_replace(" +", " ", $text);
$text = trim($text);
$text = htmlspecialchars($text);
$text = mysql_escape_string($text);
    return $text;
}
?>
