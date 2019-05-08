<html><body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Trottinette </title>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
	integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
	integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
	crossorigin=""></script>

	<?php include("global.php");
				session_start();
				include("header.php");
				include("../manager.php")?>

	<br><br><br>

	<form action="trottinette.php" method="GET">

		<input type="text" name="ID" value="<?php if (!empty($_GET['ID'])) echo $_GET['ID'] ?>">
		<input type="submit" value="Search">
	</form>

	<?php
		if (!empty($_GET["ID"]) || $_GET["ID"] == "0") {
			echo "<h1>ID is defined</h1>";

			if (false) {
				echo "<h1 style = 'color: red'>Wrong ID<h1>";
				exit();
			}

			$informations = getScooterInfo($_GET["ID"]);

		}

		else exit();
	?>
	<div class = "w3-row"><br>
		<div class = "w3-half">
			<img src="trottinette.jpg" alt="Trottinette">
		</div>

		<div class = "w3-half">
			Scooter ID : <?php echo $informations["scooterID"]; ?><br>
			Commissioning Date : <?php echo $informations["commissioningDate"]; ?><br>
			Model Number : <?php echo $informations["modelNumber"]; ?><br>
			Battery Level : <?php echo $informations["batteryLevel"]; ?><br>
		</div>
	</div>
	<br>
	<div class = "w3-row"><br>
		<div class = "w3-half">
			<div id="map" style=" position:relative; margin-left: auto; margin-right: auto; width: 400px; height: 400px; border: 1px solid #AAA;"></div>
		</div>

		<div class = "w3-half">
			plainte
		</div>
	</div>
	<br><br>

	<script>
		var map = L.map('map');
		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		subdomains: ['a', 'b', 'c']
		}).addTo(map);
	</script>
	<?php
		echo "<script>
				map.setView([".$informations["locationX"].",".$informations["locationY"]."],15);
				L.marker([".$informations["locationX"].",".$informations["locationY"]."]).addTo(map);
			</script>";
	?>

	<?php include("footer.php");?>
</body></html>
