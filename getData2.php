<?php $con=mysql_connect("localhost","monitor","Caroline") or die("Failed to connect with database!!!!");
mysql_select_db("Temps", $con); 

//now we're logged onto mysql, selected the Temps db

//this will grab the query string and we'll do something with it later
//$uh = $_SERVER['QUERY_STRING'];


//echo $uh;

date_default_timezone_set('EST');
	//for the day query
	//echo date("Y-m-d");
	
	$querytime = date("Y-m-d", strtotime( '-1 days' ) );
	
	//echo date("Y-m-d", strtotime( '-1 days' ) );



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

//the sql server query
$MySqlStr = "SELECT * FROM tempdat WHERE Zone='" . $where . "' AND Date='" . $querytime . "'";



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
        array('label'=> 'Date', type=>'string'),
        array('label'=> 'Time', type=>'string'),
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
        $temp[]=array('v' => $r['Time']);
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