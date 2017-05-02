<?php
	error_reporting(0);
	
	/* Attempt MySQL server connection. Assuming you are running MySQL
	server with default setting (user 'root' with no password) */
	$link = mysqli_connect("localhost", "root", "toor", "raspberry");
 
	// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
 
	// Attempt select query execution
	$sql = "SELECT * FROM bmp180 order by date desc limit 5";
	$pressure = array();
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$pressure[] = $row['pressure'];
				$time[] = $row['date'];
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
		array(date('h:i A', strtotime($time[0])),$pressure[0]/3386.38816, 2),
		array(date('h:i A', strtotime($time[1])),$pressure[1]/3386.38816, 2),
		array(date('h:i A', strtotime($time[2])),$pressure[2]/3386.38816, 2),
		array(date('h:i A', strtotime($time[3])),$pressure[3]/3386.38816, 2),
		array(date('h:i A', strtotime($time[4])),$pressure[4]/3386.38816, 2),
	);
	$plot->SetDataValues($temperature_data);
	
	$plot->SetYTickIncrement(2);
	
	//Set titles
	$plot->SetTitle("Pressure Readings");
	$plot->SetYTitle("Inches");
	$plot->SetXTitle('Time of Reading');
	
	//Select a plot area and force ticks to nice values:
	$plot->SetPlotAreaWorld(NULL, 20, NULL, 32);
	
	//Turn off X axis ticks and labels because they get in the way:
	$plot->SetXTickLabelPos('none');
	$plot->SetXTickPos('none');

	
	$plot->SetMarginsPixels(60, 40, 30, 70);
	
	$plot->SetDataColors(array('green'));
	//$plot->SetLegend(array('Pressure'));
	$plot->SetLegendPosition(0, 0, 'image', 0, 0, 25, 160);

	//Draw it
	$plot->DrawGraph();
	
	// Close connection
	mysqli_close($link);
?>