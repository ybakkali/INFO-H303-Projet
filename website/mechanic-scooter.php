<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
	if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
			if (isset($_GET["delete"]) && $_GET["delete"] == 'true')
				removeScooter($_GET["ID"]);
			else if (isset($_GET["repair"]) && $_GET["repair"] == 'true')
				repairScooter($_GET["ID"]);
			else if (isset($_GET["fix"]) && $_GET["fix"] == 'true')
				fixScooter($_GET["ID"]);
	}
	elseif (isset($_GET["add"]) && $_GET["add"] == 'true') {
	addScooter($_GET["modelNumber"],$_GET["x"],$_GET["y"]);
	}
?>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
		integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
		crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
		integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
		crossorigin=""></script>
		<style>
		#scrolltable { margin-top: 35px; height: 80%; overflow: auto; }
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: center;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px; cursor: pointer; }
		.c1 {width: 10%;}
		.c2 {width: 20%;}
		.c3 {width: 10%;}
		.c4 {width: 10%;}
		.c5 {width: 10%;}
		.c6 {width: 10%;}
		.c7 {width: 10%;}
		.c8 {width: 10%;}
		</style>
		<title> DataBase Project - Scooter </title>
	</head>
	<body>
		<?php include("header.html")?>
		<script src="popup.js"></script>
		<script>
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

		function addScooter() {
			window.location = "mechanic-scooter.php?modelNumber="+document.getElementById("modelNumber").value+"&x="+marker.getLatLng().lat+"&y="+marker.getLatLng().lng+"&add=true&sortBy=totalComplaints";
		}
		</script>

		<br><br><div id="scrolltable">
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
	  </div><br>
		<button onclick="toggleModal()">Add New Scooter</button>
		<div class="modal" id="modal">
			<div class="modal-content" style="background-color:white; top:10%">
				<h1>Add New Scooter</h1>
					<label>Model Number</label><br>
					<input type="text" id="modelNumber" placeholder="Enter Model Number" required><br>
					<br>
					<div id="map" style="position:relative; margin-left: auto; margin-right: auto; width: 100%; height: 100%"></div>
					<br>
					<button onclick="addScooter()">Done</button>
			</div>
		</div>
		<script>
			window.addEventListener("click", windowOnClick);

			var map = L.map('map');
			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			subdomains: ['a', 'b', 'c']
			}).addTo(map);
			map.setView([50.8499268,4.37],15);

			var scooter = L.icon({
				iconUrl: "images/marker-blue.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});

			var marker = L.marker([50.8499268,4.37],{icon:scooter,draggable:true, autoPan:true}).addTo(map);
		</script>
	</body>
</html>
