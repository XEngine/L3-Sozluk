<?php
$con = mysql_connect("localhost","root","troll159753");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("beta", $con);
mysql_query("SET NAMES utf8");
	function turkishreplace($sData){
		$newphrase=$sData;
		$newphrase = str_replace("Ü","U;",$newphrase);
		$newphrase = str_replace("Ş","S",$newphrase);
		$newphrase = str_replace("Ğ","G",$newphrase);
		$newphrase = str_replace("Ç","C",$newphrase);
		$newphrase = str_replace("İ","I",$newphrase);
		$newphrase = str_replace("Ö","O",$newphrase);
		$newphrase = str_replace("ü","u",$newphrase);
		$newphrase = str_replace("ş","s",$newphrase);
		$newphrase = str_replace("ç","c",$newphrase);
		$newphrase = str_replace("ı","i",$newphrase);
		$newphrase = str_replace("ö","o",$newphrase);
		$newphrase = str_replace("ğ","g",$newphrase);
		return $newphrase;
	}

$query = mysql_query("select * from xr_threads");
while($row = mysql_fetch_array($query))
{
	echo $row[0].' id - '.$row[1].' donusturuldu'.PHP_EOL;
	$alias = str_replace(" ","-",turkishreplace($row[1]));
	$alias = $alias;
	$update = mysql_query("UPDATE xr_threads SET alias='$alias' where title='$row[1]'");
}

?>
