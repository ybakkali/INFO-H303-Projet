<html><body>
<title>DataBase Project - Map</title>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
	integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
	integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
	crossorigin=""></script>

	<?php include("global.php");?>
	<?php session_start();?>
	<?php include("header.html");?>
	<?php include("../manager.php");?>
		<div id="map" style=" position:relative; margin-left: auto; margin-right: auto; top:60px; width: 100%; height: 93.9%;"></div>
<script>
	var map = L.map('map').setView([50.8499268,4.37],15);
	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	subdomains: ['a', 'b', 'c']
	}).addTo(map);
	var user = L.icon({
		iconUrl: "images/marker-red.png",
		iconSize: [40,40],
		iconAnchor: [20,40]
	});
	var scooter = L.icon({
		iconUrl: "images/marker-blue.png",
		iconSize: [40,40],
		iconAnchor: [20,40]
	});
	var userMarker = L.marker([0,0],{icon:user}).addTo(map);
	userMarker.bindPopup("<p style = 'text-align: center'>You are here</p>");
	if (navigator.geolocation)
	    navigator.geolocation.watchPosition(updateUserMarker);

	function updateUserMarker(position) {
		userMarker.setLatLng([position.coords.latitude,position.coords.longitude]);
	}
</script>

<?php
		$result = AveScooterPos();
		echo "<script>";
		foreach ($result as $scooter) {
				echo 'var marker = L.marker(['.$scooter['locationX'].', '.$scooter['locationY'].'],{icon:scooter}).addTo(map);
							marker.bindPopup("<p style = \"text-align: center\"><a href = \"trottinette.php?ID='.$scooter['scooterID'].'\">'.$scooter['scooterID'].'</a></p>");';
		}
		echo "</script>";
	?>

</body>
</html>
