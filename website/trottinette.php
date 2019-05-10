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
				include("../manager.php");
				if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
				?>

	<br><br><br>

	<form action="trottinette.php" method="GET">

		<input type="text" name="ID" value="<?php if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) echo $_GET["ID"] ?>">
		<input type="submit" value="Search">
	</form>

	<?php
		if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
			$informations = getScooterInfo($_GET["ID"]);
			$complains = getScooterComplains($_GET["ID"]);
		}

		else exit();
	?>
	<div class = "w3-row"><br>
		<div class = "w3-half">
			<img src="trottinette.jpg" alt="Trottinette">
		</div>

		<div class = "w3-half">
			<h2>Scooter ID : <?php echo $informations["scooterID"]; ?></h2><br>
			<h2>Commissioning Date : <?php echo $informations["commissioningDate"]; ?></h2><br>
			<h2>Model Number : <?php echo $informations["modelNumber"]; ?></h2><br>
			<h2>Battery Level : <?php echo $informations["batteryLevel"]; ?></h2><br>
		</div>
	</div>
	<br>
	<div class = "w3-row"><br>
		<div class = "w3-half">
			<div id="map" style=" position:relative; margin-left: auto; margin-right: auto; width: 400px; height: 400px; border: 1px solid #AAA;"></div>
		</div>

		<div class = "w3-half">

			<h2 style = "margin-right:285px">Complains</h2>
			<div style = "text-align: center; overflow: auto; height: 30%;">
				<table style = "width: 60%">
				  <tr>
				    <th>Date</th>
				    <th>Introduced by</th>
				    <th>Description</th>
				  </tr>
					<?php
					foreach ($complains as $complain) {
							echo "<tr>
										<th>".$complain["date"]."</th>
										<th>".$complain["userID"]."</th>
										<th>".$complain["description"]."</th>
										</tr>";
					}
					?>
				</table>
			</div>
			<br>
			<a href = <?php echo "complain.php?ID=".$informations["scooterID"] ?> style = "margin-right:285px"><input type="submit" value="Add a new complain"></a>
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

</body></html>
