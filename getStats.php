
<style>
table {
    width: 50%;
    margin-left:25%; 
    margin-right:25%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>





<?php $con=mysql_connect("localhost","Monitor","***********") or die("Failed to connect with database!!!!");
mysql_select_db("Monitor", $con); 

//now we're logged onto mysql, selected the Monitor db

//tempdat table looks like 

/*
---------------------------
example data: Table (Chart)
--------------------------
Date	Time	Zone	Temp	State	FanS  Outside Commanded
2014-05-13	21:19:42	Downstairs	78.5	0	0  78.3   76.2
*/


date_default_timezone_set('EST');


$where = $_GET['where'];
$startdate = $_GET['startd'];
$endate = $_GET['endd'];

$MySqlStrZONE = "SELECT  Zone FROM `tempdat` GROUP BY `Zone`";
$MySqlStrON = "SELECT  Zone, COUNT(*) FROM `tempdat` WHERE `Thermostat State`!=0 AND `Date` BETWEEN '" . $startdate . "' AND '" . $endate . "'  GROUP BY `Zone`";
$MySqlStrOFF = "SELECT  Zone, COUNT(*) FROM `tempdat` WHERE `Thermostat State`=0 AND `Date` BETWEEN '" . $startdate . "' AND '" . $endate . "'  GROUP BY `Zone`";

$sthZONE = mysql_query($MySqlStrZONE) or die(mysql_error());;
$sthON = mysql_query($MySqlStrON) or die(mysql_error());;
$sthOFF = mysql_query($MySqlStrOFF) or die(mysql_error());;




echo "<table>
<tr>
<th>Zone</th>
<th>Datapoints ON</th>
<th>Datapoints OFF</th>
<th>Duty Cycle</th>
</tr>";


$count = 0;
while ($row = mysql_fetch_array($sthZONE)){
	
	$rowoff[] = mysql_fetch_array($sthOFF);
    $rowON = mysql_fetch_array($sthON);
    
	echo "<tr>";
	echo "<td>" . $row['Zone'] . "</td>";
    if ($rowON['COUNT(*)']) {
        echo "<td>" . $rowON['COUNT(*)'] . "</td>";
    } else {
        echo "<td>" . 0 .  "</td>";
    }
    
    if ($rowoff[$count]['COUNT(*)']) {
        echo "<td>" . $rowoff[$count]['COUNT(*)'] . "</td>";
    } else {
        echo "<td>" . 0 .  "</td>";
    }
    
	echo "<td>" . (int) ($rowON['COUNT(*)'] / ($rowON['COUNT(*)'] + $rowoff[$count]['COUNT(*)']) * 100) . "</td>";
	echo "</tr>";
    
	$count=$count+1;
}

echo "</table>";



mysql_close($con);
?>