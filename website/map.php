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
	<?php include("header.php");?>
	<?php include("../manager.php");?>
	<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
		<div id="map" style=" position:relative; margin-left: auto; margin-right: auto; top:125px; width: 80%; height: 80%; border: 1px solid #AAA;"></div>
	</header>
<script>
	var map = L.map('map').setView([50.8499268,4.37],15);
	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	subdomains: ['a', 'b', 'c']
	}).addTo(map);
</script>

<?php
		$result = AveScooterPos();
		echo "<script>";
		foreach ($result as $scooter) {
				echo 'var marker = L.marker(['.$scooter['locationX'].', '.$scooter['locationY'].']).addTo(map);
							marker.bindPopup("<b><a href = \"trottinette.php?ID='.$scooter['scooterID'].'\">'.$scooter['scooterID'].'</a></b><br>I am a popup.");';
		}
		echo "</script>";
	?>

</body>
</html>
