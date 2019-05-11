<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!isloggedIn()) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
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
		<title> DataBase Project - Trottinette </title>
		<style>
		#scrolltable { margin-top: 80px; height: 30%; overflow: auto; max-height: 200px}
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: center;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px; }
		.c1 {width: 1px;}
		.c2 {width: 30px;}
		.c3 {width: 160px;text-align: left;}
		.grey {background-color: rgba(128,128,128,.25);}
		.red {background-color: rgba(255,0,0,.25);}
		</style>
	</head>
	<body>
		<?php include("header.html")?>
		<br><br><br>
		<form action="trottinette.php" method="GET">

			<input type="text" name="ID" placeholder="Enter Scooter ID" value="<?php if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) echo $_GET["ID"] ?>" required>
			<input type="submit" value="Search">
		</form>

		<?php
			if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
				$informations = getScooterInfo($_GET["ID"]);
				$complaints = getScooterComplains($_GET["ID"]);
			}

			else exit();
		?>
		<div class = "w3-row"><br>
			<div class = "w3-half">
				<img src="trottinette.jpg" alt="Trottinette">
			</div>

			<div class = "w3-half">
				<table>
				  <colgroup>
				    <col class="grey"/>
				    <col class="red"/>
				  </colgroup>
					  <thead>
					    <tr>
					      <th>Scooter ID</th>
					      <th><?php echo $informations["scooterID"]; ?></th>
					    </tr>
					  </thead>
					  <tbody>
					    <tr>
					      <th>Commissioning Date</th>
					      <td><?php echo $informations["commissioningDate"]; ?></td>
					    </tr>
					    <tr>
					      <th>Model Number</th>
					      <td><?php echo $informations["modelNumber"]; ?></td>
					    </tr>
						<tr>
						  <th>Battery Level</th>
						  <td><?php echo $informations["batteryLevel"]; ?></td>
						</tr>
					  </tbody>
					</table>
			</div>
		</div>
		<br>
		<div class = "w3-row"><br>
			<div class = "w3-half">
				<div id="map" style=" position:relative; margin-left: auto; margin-right: auto; width: 400px; height: 400px; border: 1px solid #AAA;"></div>
			</div>

			<div class = "w3-half">

				<h2>Complaints</h2>
					<div id="scrolltable">
						<table>
							<thead>
								  <tr>
								  <th><div>Date</div></th>
								  <th><div>Introduced by</div></th>
								  <th><div>Description</div></th>
								  </tr>
							</thead>
							<tbody>
								<?php
								foreach ($complaints as $complaint) {
										echo "  <tr>
												<th class = 'c1'>".$complaint["date"]."</th>
												<th class = 'c2'>".$complaint["userID"]."</th>
												<th class = 'c3'>".$complaint["description"]."</th>
											    </tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				<br>
				<a href = <?php echo "complain.php?ID=".$informations["scooterID"] ?> ><input type="submit" value="Add a new complaint"></a>
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

	</body>
</html>
