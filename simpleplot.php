<?php
	error_reporting(0);
	
	/* Attempt MySQL server connection. Assuming you are running MySQL
	server with default setting (user 'root' with no password) */
	$link = mysqli_connect("localhost", "root", "", "raspberry");
 
	// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
 
	// Attempt select query execution
	$sql = "SELECT * FROM dht11 order by time desc limit 5";
	$data = array();
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$data[] = $row['temp'];
				$time[] = $row['time'];
			}
			// Free result set
			mysqli_free_result($result);
		} else {
			echo "ERROR";
		}
	}
	
	//Include the code
	require_once 'phplot.php';
	
	//Define the object
	$plot = new PHPlot(1000, 200);
	$plot->SetImageBorderType('plain');

	//Define some data
	$temperature_data = array(
		array(date('h:i A', strtotime($time[0])),$data[0]),
		array(date('h:i A', strtotime($time[1])),$data[1]),
		array(date('h:i A', strtotime($time[2])),$data[2]),
		array(date('h:i A', strtotime($time[3])),$data[3]),
		array(date('h:i A', strtotime($time[4])),$data[4]),
	);
	$plot->SetDataValues($temperature_data);
	
	$plot->SetYTickIncrement(10);
	
	//Set titles
	$plot->SetTitle("Temperature Readings");
	$plot->SetYTitle("Degrees Fahrenheit");
	$plot->SetXTitle('Time of Reading');
	
	//Select a plot area and force ticks to nice values:
	$plot->SetPlotAreaWorld(NULL, -20, NULL, 60);
	
	//Turn off X axis ticks and labels because they get in the way:
	$plot->SetXTickLabelPos('none');
	$plot->SetXTickPos('none');

	
	$plot->SetMarginsPixels(60, 40, 30, 70);
	
	$plot->SetDataColors(array('green'));
	$plot->SetLegend(array('Temperature'));
	$plot->SetLegendPosition(0, 0, 'image', 0, 0, 25, 160);

	//Draw it
	$plot->DrawGraph();
	
	// Close connection
	mysqli_close($link);
?>