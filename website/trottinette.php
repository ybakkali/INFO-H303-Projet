<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!isloggedIn()) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
?>
<script src="popup.js"></script>

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
		#scrolltable { margin-top: 80px; height: 30%; overflow: auto; max-height: 260px}
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: left;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px; cursor: pointer; }
		.c1 {width: 10px;}
		.c2 {width: 60px;}
		.c3 {width: 60px;}
		.c4 {width: 100px;text-align: left;}
		.grey {background-color: rgba(128,128,128,.25);}
		.red {background-color: rgba(255,0,0,.25);}
		.button {
		  display: inline-block;
		  padding: 10px 15px;
		  font-size: 24px;
		  cursor: pointer;
		  text-align: center;
		  text-decoration: none;
		  outline: none;
		  color: #fff;
		  background-color: #FFA07A;
		  border: none;
		  border-radius: 15px;
		  box-shadow: 0 9px #999;
		  width: 160px;
		}

		.button:hover {background-color: #ADD8E6}

		.button:active {
		  background-color: #3e8e41;
		  box-shadow: 0 5px #666;
		  transform: translateY(4px);
		}
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
				if (isset($_GET["reserve"]) && !empty('$_GET["reserve"]'))
					reserveScooter($_GET["ID"],$_SESSION["ID"]);
				elseif (isset($_GET["reload"]) && !empty('$_GET["reload"]'))
					reloadScooter($_GET["ID"],$_SESSION["ID"]);
				elseif (isset($_GET["bringback"]) && !empty('$_GET["bringback"]'))
					bringBackScooter($_GET["ID"],$_SESSION["ID"],$_GET["x"],$_GET["y"]);

				$informations = getScooterInfo($_GET["ID"]);
				if (isset($_GET["sortBy"]) && !empty('$_GET["sortBy"]'))
					$complaints = getScooterComplains($_GET["ID"],$_GET["sortBy"]);
				else
					$complaints = getScooterComplains($_GET["ID"],'date');
			}
			else exit();
		?>
		<script>

		function reloadScooter(id) {
			if (confirm("Do you want to realod the scooter "+id+" ?"))
				window.location = "trottinette.php?ID="+id +"&reload=true";
		}

		function reserveScooter(id) {
			if (confirm("Do you really want to reserve the scooter "+id+" for 3€ ?"))
				window.location = "trottinette.php?ID="+id +"&reserve=true";
		}

		function bringBackScooter(id,x,y) {
			if (confirm("Are you sure to put it here ?"))
				window.location = "trottinette.php?ID="+id+"&bringback=true&x="+x+"&y="+y+"";
		}
		</script>
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
						<tr>
						  <th>Availability</th>
						  <td><?php echo $informations["availability"]; ?></td>
						</tr>
					  </tbody>
					</table>
			</div>
			<div class= "w3-row">
				<?php
					if ($_SESSION["type"] != "unregistered") {
						echo '<a onclick = "reloadScooter('.$_GET["ID"].')" ><button class="button">Reload it</button></a>';
						echo '<button class="button" onclick="document.getElementById(\'map\').style.visibility = \'hidden\';toggleModal();">Bring back</button>';
					}
				?>
				<button onclick = "reserveScooter(<?php echo $_GET["ID"]; ?>)" class="button">Reserve it</button>
				<a href = <?php echo "complain.php?ID=".$informations["scooterID"] ?> ><button class="button">Complain</button></a>
			</div>
		</div>
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
									  <th onclick="window.location = 'trottinette.php?ID=<?php echo $_GET["ID"]?>&sortBy=date'"><div>Date</div></th>
									  <th onclick="window.location = 'trottinette.php?ID=<?php echo $_GET["ID"]?>&sortBy=userID'"><div>By</div></th>
									  <th onclick="window.location = 'trottinette.php?ID=<?php echo $_GET["ID"]?>&sortBy=state'"><div>State</div></th>
									  <th onclick="window.location = 'trottinette.php?ID=<?php echo $_GET["ID"]?>&sortBy=description'"><div>Description</div></th>
								  </tr>
							</thead>
							<tbody>
								<?php
								foreach ($complaints as $complaint) {
										echo "  <tr>
												<th class = 'c1'>".$complaint["date"]."</th>
												<th class = 'c2'>".$complaint["userID"]."</th>
												<th class = 'c3'>".$complaint["state"]."</th>
												<th class = 'c4'>".$complaint["description"]."</th>
											    </tr>";
								}
								?>
							</tbody>
						</table>
					</div>
			</div>
		</div>
		<div class="modal" id="modal">
			<div class="modal-content">
				<div id="popupmap" style="position:relative; margin-left: auto; margin-right: auto; width: 100%; height: 100%"></div>
				<br>
				<button onclick="bringBackScooter(<?php echo $_GET["ID"]; ?>,nextMarker.getLatLng().lat, nextMarker.getLatLng().lng)">Done</button>
			</div>
		</div>

		<script>
			var map = L.map('map');
			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			subdomains: ['a', 'b', 'c']
			}).addTo(map);
			map.setView([<?php 	if ($informations["availability"] != "defective") echo $informations["locationX"].",".$informations["locationY"];
													else echo "0,0";
										?>],15);

			var scooter = L.icon({
				iconUrl: "images/marker-<?php if ($informations["availability"] == "available") echo "blue";
																			elseif ($informations["availability"] == "occupy") echo "red";
																			elseif ($informations["availability"] == "inReload") echo "yellow";
																			else echo "black";
																?>.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});

			L.marker([<?php if ($informations["availability"] != "defective") echo $informations["locationX"].",".$informations["locationY"];
											else echo "0,0";
								?>],{icon:scooter}).addTo(map);

			// PopUpMap
			var popupmap = L.map('popupmap').setView([<?php echo $informations["locationX"].",".$informations["locationY"]?>],16);
			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			subdomains: ['a', 'b', 'c']
		}).addTo(popupmap);
			var lastIcon = L.icon({
				iconUrl: "images/marker-yellow.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});
			var nextIcon = L.icon({
				iconUrl: "images/marker-blue.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});
			var lastMarker = L.marker([<?php echo $informations["locationX"].",".$informations["locationY"]?>],{icon:lastIcon}).addTo(popupmap);
			var nextMarker = L.marker([<?php echo ($informations["locationX"]-0.001).",".$informations["locationY"]?>],{icon:nextIcon,draggable:true, autoPan:true}).addTo(popupmap);
			nextMarker.bindPopup("Drag me!").openPopup();

			function windowOnClick(event) {
			 if (event.target ===  document.getElementById("modal")) {
				 toggleModal();
				 document.getElementById("map").style.visibility = "visible";
			 }
			}
			window.addEventListener("click", windowOnClick);

		</script>
	</body>
</html>
