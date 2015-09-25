<?php $con=mysql_connect("localhost","monitor","Caroline") or die("Failed to connect with database!!!!");
mysql_select_db("Temps", $con); 

//now we're logged onto mysql, selected the Temps db


//echo $_SERVER['QUERY_STRING'];
$where = $_GET['where'];
$when = $_GET['when'];

if ($when == 'yest') {
	$timehelp = '-1';
} 
else {
	$timehelp='0';
}
//echo $timehelp;
$querytime = date("Y-m-d", strtotime( $timehelp . ' days' ) );

//echo $querytime;

$MySqlStr = "SELECT  Zone, COUNT(*) FROM `tempdat` WHERE `Thermostat State`=2 AND `Date`=CURDATE() GROUP BY `Zone`";

$sth = mysql_query($MySqlStr) or die(mysql_error());;

$count=0;
$down=0;
$up=0;
while ($row[] = mysql_fetch_array($sth)){
	echo "There are " . $row[$count]['COUNT(*)']. " datapoints on cool for " . $row[$count]['Zone'].".";
	echo "<br />" ;
	$count=$count+1;
}


/*
---------------------------
example data: Table (Chart)
--------------------------
Date	Time	Zone	Temp	State	FanS  Outside Commanded
2014-05-13	21:19:42	Downstairs	78.5	0	0  78.3   76.2
*/

mysql_close($con);
?>