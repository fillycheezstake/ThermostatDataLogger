<?php $con=mysql_connect("localhost","Monitor","*********") or die("Failed to connect with database!!!!");
mysql_select_db("Monitor", $con); 

//now we're logged onto mysql, selected the Temps db


date_default_timezone_set('EST');



//echo $_SERVER['QUERY_STRING'];
$where = $_GET['where'];
$startdate = $_GET['startd'];
$endate = $_GET['endd'];

if ($startdate == $endate) {
	$querytime = $endate;
    //the sql server query
    $MySqlStr = "SELECT * FROM tempdat WHERE Zone='" . $where . "' AND Date='" . $querytime . "'";
} else {
    //the sql server query
    $MySqlStr = "SELECT * FROM tempdat WHERE Zone='" . $where . "' AND Date between '" . $startdate . "' and '" . $endate . "'";
}


$sth = mysql_query($MySqlStr);

/*
---------------------------
example data: Table (Chart)
--------------------------
Date	Time	Zone	Temp	State	FanS  Outside Commanded
2014-05-13	21:19:42	Downstairs	78.5	0	0  78.3   76.2
*/



$table=array();

$table['cols']=array(
        array('label'=> 'Date', type=>'date'),
        array('label'=> 'Time', type=>'datetime'),
        array('label'=> 'Zone', type=>'number'),
        array('label'=> 'Temp', type=>'number'),
		array('label'=> 'State', type=>'number'),
		array('label'=> 'FanS', type=>'number'),
		array('label'=> 'Outside', type=>'number'),
		array('label'=> 'Commanded', type=>'number')
);

$rows=array();
while($r=mysql_fetch_assoc($sth)){
        $temp=array();
        $temp[]=array('v' => $r['Date']);
		
		//for google charts "datetime" which looks like the string 
		//"Date(Year, Month, Day, Hours, Minutes, Seconds, Milliseconds)"
		
		$datearr = explode("-", $r['Date']);
		$timearr = explode(":", $r['Time']);
		//month - 1 because javascript & SQL have different month notation - javacript starts at 0, SQL starts at 1
		$datetimer = "Date(" . $datearr[0] . "," . (int) ($datearr[1] - 1) . "," . $datearr[2] . "," . $timearr[0] . "," . $timearr[1] . "," . $timearr[2] . ")";
		//echo $datetimer;
		
		$temp[]=array('v' => $datetimer);
		
		// For google charts datatype "timeofday"
		/*
		$temptime = $r['Time'];
		$temptimearr = explode(":",$temptime);
		for ($x = 0; $x <= 2; $x++) {
			$cheese[$x] =  (int)$temptimearr[$x];
		}
        $temp[]=array('v' => $cheese);
		//end of google charts timeofday
		*/
		
		$temp[]=array('v' => $r['Zone']);
        $temp[]=array('v' => (double) $r['Temperature']);
		$temp[]=array('v' => (double) $r['Thermostat State']);
		$temp[]=array('v' => (double) $r['Fan State']);
		$temp[]=array('v' => (double) $r['OutsideTemp']);
		$temp[]=array('v' => (double) $r['CommandTemp']);
		

        $rows[]=array('c' => $temp);
}

$table['rows']=$rows;

$jsonTable = json_encode($table);
echo $jsonTable;

mysql_close($con);
?>
