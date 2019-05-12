<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
			integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
			crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
		integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
		crossorigin=""></script>
		<script src="popup.js"></script>
		<title>DataBase Project - Requests</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Requests</h1>
				<label>Request 1</label><br><br>
				<div class="w3-container w3-stretch w3-large" style="max-height: 500px;overflow: auto;">
					<?php
						$r1 = getR1();
						$i = $j = 0;
						while ($i < sizeof($r1)) {
							echo "<div class='w3-row-padding'>";
							$j = 0;
							while ($j < 3 && $i + $j < sizeof($r1)) {
									echo "<div class='w3-col l4 w3-center w3-border'>
									<p>".$r1[$i+$j]["scooterID"]."</p>
									<p>".$r1[$i+$j]["locationX"]." ".$r1[$i+$j]["locationY"]."</p>
									<p><button onclick='showOnMap(".$r1[$i+$j]["locationX"].",".$r1[$i+$j]["locationY"].")'>Show On Map</button></p>
									</div>";
									$j++;
							}
							echo "</div>";
							$i += $j;
						}
					?>
				</div><br>
				<label>Request 2</label><br><br>
				<div class="w3-container w3-stretch w3-large" style="max-height: 500px;overflow: auto;">
					<?php
						$r2 = getR2();
						$i = $j = 0;
						while ($i < sizeof($r2)) {
							echo "<div class='w3-row-padding'>";
							$j = 0;
							while ($j < 6 && $i + $j < sizeof($r2)) {
									echo "<div class='w3-col l2 w3-center w3-border'>
									<p>".$r2[$i+$j]["userID"]."</p>
									</div>";
									$j++;
							}
							echo "</div>";
							$i += $j;
						}
					?>
				</div><br>

				<label>Request 3</label><br><br>
				<?php
				 	$r3 = getR3();
					echo "Scooter ID : ".$r3;
				?><br><br>

				<label>Request 4</label><br><br>
				<div class="w3-container w3-stretch w3-large" style="max-height: 500px;overflow: auto;">
					<?php
						$r4 = getR4();
						$i = $j = 0;
						while ($i < sizeof($r4)) {
							echo "<div class='w3-row-padding'>";
							$j = 0;
							while ($j < 6 && $i + $j < sizeof($r4)) {
									echo "<div class='w3-col l2 w3-center w3-border'>
									<p>".$r4[$i+$j]["scooterID"]."</p>
									</div>";
									$j++;
							}
							echo "</div>";
							$i += $j;
						}
					?>
				</div><br>

				<label>Request 5</label><br><br>
				<table class="w3-table w3-centered w3-responsive w3-large" style="max-height: 500px;overflow: auto";>
					<tr>
						<th>User ID</th>
						<th>Average Trips Duration</th>
						<th>Number Of Trips</th>
						<th>Total Amount</th>
					</tr>
					<?php
						$r5 = getR5();
						foreach ($r5 as $user) {
							echo "<tr>
											<td>".$user["userID"]."</td>
											<td>".$user["Average duration"]."</td>
											<td>".$user["Total trips"]."</td>
											<td>".$user["Total amount (€)"]." €</td>
										</tr>";
						}
					?>
				</table>

			</div>
			<div class="modal" id="modal">
				<div class="modal-content">
					<div id="map" style="position:relative; margin-left: auto; margin-right: auto; width: 100%; height: 100%"></div>
				</div>
			</div>
			<script>
				var map = L.map('map').setView([0,0],13);
				L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
				subdomains: ['a', 'b', 'c']
				}).addTo(map);
				var scooter = L.icon({
					iconUrl: "images/marker-blue.png",
					iconSize: [40,40],
					iconAnchor: [20,40]
				});
				var scooterMarker = L.marker([0,0],{icon:scooter}).addTo(map);

				function showOnMap(x,y) {
					scooterMarker.setLatLng([x,y]);
					map.setView([x,y],15)
					toggleModal();
				}
				window.addEventListener("click", windowOnClick);
			</script>
		</header>
	</body>
</html>
