<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<style>
		#scrolltable { margin-top: 35px; height: 500px; overflow: auto; }
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: center;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px; cursor: pointer; }
		.c1 {width: 87px;}
		.c2 {width: 170px;}
		.c3 {width: 120px;}
		.c4 {width: 90px;}
		.c5 {width: 115px;}
		.c6 {width: 132px;}
		.c7 {width: 130px;}
		.c8 {width: 100px;}
		</style>
		<title> DataBase Project - Scooter </title>
	</head>
	<body>
		<?php include("global.php");
					session_start();
					include("header.php");
	        include("../manager.php");
					if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) echo "<script>window.location = 'home.php';</script>";
		?>

		<?php

		  if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
			 		if (isset($_GET["delete"]) && $_GET["delete"] = 'true')
		      			removeScooter($_GET["ID"]);
					else if (isset($_GET["repair"]) && $_GET["repair"] = 'true')
						repairScooter($_GET["ID"]);
					else if (isset($_GET["fix"]) && $_GET["fix"] = 'true')
						fixScooter($_GET["ID"]);
			}
		?>

		<script>
		//window.history.pushState('', 'DataBase Project - Scooter', 'mechanic-scooter.php');
		function deleteScooter(id) {
			if (confirm("Do you really want to delete the scooter "+id+" ?"))
				window.location = "mechanic-scooter.php?ID="+id +"&delete=true&sortBy=totalComplaints";
		}

		function repairScooter(id) {
			if (confirm("Do you really want to repair the scooter "+id+" ?"))
				window.location = "mechanic-scooter.php?ID="+id +"&repair=true&sortBy=totalComplaints";
		}

		function fixScooter(id) {
			if (confirm("Is the scooter "+id+" really repaired?"))
				window.location = "mechanic-scooter.php?ID="+id +"&fix=true&sortBy=totalComplaints";
		}
		</script>

		<div id="scrolltable">
	    <table>
			<thead>
				<tr>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=scooterID'"><div>Scooter ID</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=commissioningDate'"><div>Commissioning Date</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=modelNumber'"><div>Model Number</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=totalComplaints'"><div>Complaints</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=batteryLevel'"><div>Battery Level</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=locationX'"><div>Location</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=lastLocationTime'"><div>Last Time Used</div></th>
		        <th onclick="window.location = 'mechanic-scooter.php?sortBy=availability'"><div>Availability</div></th>
		        <th coldiv="3"><div>Options</div></th>
			    </tr>
			</thead>
			<tbody>
				<tr>
				<?php
				$scooters = getAllScootersInfo($_GET["sortBy"]);
				foreach ($scooters as $scooter) {
					$complaints = ($scooter["totalComplaints"] != NULL) ? $scooter["totalComplaints"] : 0;
					$location = ($scooter["locationX"] != NULL) ? $scooter["locationX"]." ".$scooter["locationY"] : "unknown";
					$time = ($scooter["lastLocationTime"] != NULL) ? $scooter["lastLocationTime"] : "unknown";
				  echo "<tr>
				        <td class='c1'>".$scooter["scooterID"]."</td>
				        <td class='c2'>".$scooter["commissioningDate"]."</td>
				        <td class='c3'>".$scooter["modelNumber"]."</td>
				        <td class='c4'><a href='mechanic-complaints.php?scooterID=".$scooter["scooterID"]."&sortBy=date'>".$complaints."</a></td>
				        <td class='c5'>".$scooter["batteryLevel"]."</td>
				        <td class='c6'>".$location."</td>
				        <td class='c7'>".$time."</td>
				        <td class='c8'>".$scooter["availability"]."</td>
				        <td><button onclick='deleteScooter(".$scooter["scooterID"].")'>Delete</button></td>
										<td><button onclick='repairScooter(".$scooter["scooterID"].")'>Repair</button></td>
										<td><button onclick='fixScooter(".$scooter["scooterID"].")'>Fix</button></td>
										</tr>";
				}
				?>
				</tr>
			</tbody>
	    </table>
	  </div>
	</body>
</html>
