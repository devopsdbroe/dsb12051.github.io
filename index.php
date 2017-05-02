<!DOCTYPE html>
<html>
<head>
	<title>JD Weather Station</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script>
		window.onload = function() {
			<?php
				$json_string = file_get_contents("http://api.wunderground.com/api/26a5ba3debf9e70e/geolookup/conditions/q/VT/Randolph.json");
				$json_string_2 = file_get_contents("http://api.wunderground.com/api/26a5ba3debf9e70e/geolookup/astronomy/q/VT/Randolph.json");
				$parsed_json = json_decode($json_string);
				$parsed_json_2 = json_decode($json_string_2);
				$temp = $parsed_json->{'current_observation'}->{'temp_f'};
				$humidity = $parsed_json->{'current_observation'}->{'relative_humidity'};
				$wind_mph = $parsed_json->{'current_observation'}->{'wind_mph'};
				$wind_dir = $parsed_json->{'current_observation'}->{'wind_dir'};
				$precip_today_in = $parsed_json->{'current_observation'}->{'precip_today_in'};
				$sunrise_h = $parsed_json_2->{'sun_phase'}->{'sunrise'}->{'hour'};
				$sunrise_m = $parsed_json_2->{'sun_phase'}->{'sunrise'}->{'minute'};
				$sunset_h = $parsed_json_2->{'sun_phase'}->{'sunset'}->{'hour'};
				$sunset_m = $parsed_json_2->{'sun_phase'}->{'sunset'}->{'minute'};	
			?>
		}
	</script>
	<script>
		$(function() {
			$('#dropdown').change(function(){
				$('.graph').hide();
				$('#' + $(this).val()).show();
			});
		});
	</script>
	<script>
		var myValues = localStorage.getItem("storageName");
		var myValues2 = localStorage.getItem("storageName2");
		var myValues3 = localStorage.getItem("storageName3");
		if(myValues == "temp_disconnected" && myValues2 == "press_disconnected" && myValues3 == "wind_disconnected") {
			window.onload = function() {
				document.getElementById("pressure").innerHTML = "disconnected";
				document.getElementById("humidity").innerHTML = "disconnected";
				document.getElementById("windSpeed").innerHTML = "disconnected";
				document.getElementById("windDir").innerHTML = "disconnected";
				document.getElementById("largeBold3").innerHTML = "disconnected";
				document.getElementById("largeBold4a").innerHTML = "disconnected";
				document.getElementById("largeBold4b").innerHTML = "disconnected";
			}
		} else if(myValues == "temp_connected" && myValues2 == "press_disconnected" && myValues3 == "wind_disconnected") {
			window.onload = function() {
				document.getElementById("pressure").innerHTML = "disconnected";
				document.getElementById("windSpeed").innerHTML = "disconnected";
				document.getElementById("windDir").innerHTML = "disconnected";
				
			}
		} else if(myValues == "temp_connected" && myValues2 == "press_connected" && myValues3 == "wind_disconnected") {
			window.onload = function() {
				document.getElementById("windSpeed").innerHTML = "disconnected";
				document.getElementById("windDir").innerHTML = "disconnected";
			}
		} else if(myValues == "temp_disconnected" && myValues2 == "press_disconnected" && myValues3 == "wind_connected") {
			window.onload = function() {
				document.getElementById("pressure").innerHTML = "disconnected";
				document.getElementById("largeBold3").innerHTML = "disconnected";
				document.getElementById("largeBold4a").innerHTML = "disconnected";
				document.getElementById("largeBold4b").innerHTML = "disconnected";
			}
		} else if(myValues == "temp_disconnected" && myValues2 == "press_connected" && myValues3 == "wind_connected") {
			window.onload = function() {
				document.getElementById("largeBold3").innerHTML = "disconnected";
				document.getElementById("largeBold4a").innerHTML = "disconnected";
				document.getElementById("largeBold4b").innerHTML = "disconnected";
			}
		} else if(myValues == "temp_connected" && myValues2 == "press_disconnected" && myValues3 == "wind_connected") {
			window.onload = function() {
				document.getElementById("pressure").innerHTML = "disconnected";
			}
		} else if(myValues == "temp_disconnected" && myValues2 == "press_connected" && myValues3 == "wind_disconnected") {
			window.onload = function() {
				document.getElementById("humidity").innerHTML = "disconnected";
				document.getElementById("windSpeed").innerHTML = "disconnected";
				document.getElementById("windDir").innerHTML = "disconnected";
				document.getElementById("largeBold3").innerHTML = "disconnected";
				document.getElementById("largeBold4a").innerHTML = "disconnected";
				document.getElementById("largeBold4b").innerHTML = "disconnected";
			}
		}
	</script>
</head>
<body>
	<div id="content">
		<div id="info">
			<div id="largeBold1">JD Weather Station</div>
			<span id="smallFont1">Randolph, VT</span>
		</div>
		<div id="sidebar">
			Pressure:
			<span id="pressure">
			<?php
			error_reporting(0);
				
			$servername = "localhost";
			$username = "root";
			$password = "toor";
			$dbname = "raspberry";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT pressure FROM bmp180 order by date desc limit 1";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo (round($row["pressure"] /3386.38816, 2)). " in";
				}
			} else {
				echo "ERROR";
			}
			?></span><br>
			<br>
			<br>
			Humidity:
			<span id="humidity">
			<?php
			$sql = "SELECT hum FROM dht11 order by time desc limit 1";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo $row["hum"]. "%";
				}
			} else {
				echo "ERROR";
			}
			?></span><br>
			<br>
			<br>
			Wind Speed:
			<span id="windSpeed">
			<?php
				echo "${wind_mph} mph";
			?></span><br>
			<br>
			<br>
			Wind Direction: 
			<span id="windDir">
			<?php
				echo "${wind_dir}";
			?></span><br>
			<br>
			<br>
			Rainfall: 
			<?php
				echo "${precip_today_in} in";
			?><br>
			<br>
			<br>
			Sunrise:
			<?php
				echo "${sunrise_h}".":"."${sunrise_m}"." "."AM";
			?>
			<br>
			<br>
			<br>
			Sunset:
			<?php
				$format = "${sunset_h}".":"."${sunset_m}";
				echo date('h:i', strtotime($format))." "."PM";
			?>
		</div>
		<div id="temperature">
			<span id="largeBold2">Current Temperature:</span>
			<div id="largeBold3">
				<?php
				$sql = "SELECT temp FROM dht11 order by time desc limit 1";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						echo (($row["temp"]*1.8) + 32). " °F";
					}
				} else {
					echo "ERROR";
				}
				?>
			</div>
			<br>
			<span id="largeBold4a">High:
				<?php
				$sql = "SELECT temp FROM dht11 order by temp desc limit 1";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						echo (($row["temp"] * 1.8) + 32). " °F";
					}
				} else {
					echo "ERROR";
				}
				?>
			</span>
			<span id="largeBold4b">Low:
				<?php
				$sql = "SELECT temp FROM dht11 order by temp asc limit 1";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						echo (($row["temp"] * 1.8) + 32). " °F";
					}
				} else {
					echo "ERROR";
				}
				?>
			</span><br>
			<br>
			<br>
			Last updated on
			<?php
			$sql = "SELECT time FROM dht11 order by time desc limit 1";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo date( "l", strtotime($row["time"])) . " at " . date( "h:i", strtotime($row["time"])) . " " . date( "A", strtotime($row["time"]));
				}
			} else {
				echo "ERROR";
			}
			$conn->close();
			?>
		</div>
		<span id="configButtonPosition"><a href="conf_login.html"><button id="configButtonSizeColor">Configure</button></a></span>
		<div id="select">
			<select id="dropdown" name="graph">
				<option value="" disable selected>-- Select a Graph --</option>
				<option value="temp">Temperature</option>
				<option value="pressure">Pressure</option>
				<option value="windSpeed">Wind Speed</option>
			</select>
		</div>
		<img id="temp" class="graph" src="temperature.php" alt="can't load graph">
		<img id="pressure" class="graph" src="pressure.php" alt="can't load graph" style="display:none">
		<img id="windSpeed" class="graph" src="windSpeed.php" alt="can't load graph" style="display:none">
	</div>
</body>
</html>