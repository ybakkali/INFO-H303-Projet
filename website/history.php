<!DOCTYPE html>
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
		#scrolltable {margin-left: 10px; margin-top: 80px; height: 500px; overflow: auto; }
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: left;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px;cursor: pointer; }
		.c1 {width: 200px;}
		.c2 {width: 200px;}
		.c3 {width: 200px;}
		.c4 {width: 200px;}
		</style>
		<title> DataBase Project - History </title>
	</head>
	<body>
	  <?php
	    include("global.php");
			session_start();
			include("header.php");
	    include("../manager.php");
			if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
		?>
		<script src="popup.js"></script>
		<div id="scrolltable">
		<table>
			<thead>
	        <tr>
	          <th onclick="window.location = 'history.php?sortBy=scooterID'"><div>Scooter ID</div></th>
	          <th onclick="window.location = 'history.php?sortBy=starttime'"><div>Time</div></th>
	          <th onclick="window.location = 'history.php?sortBy=duration'"><div>Duration</div></th>
	          <th onclick="window.location = 'history.php?sortBy=price'"><div>Price</div></th>
	          <th><div></div></th>
	        </tr>
			</thead>
			<tbody>
	        <?php
	        $trips = userTripsHistory($_SESSION["ID"],$_GET["sortBy"]);
	        foreach ($trips as $trip) {
	            echo "<tr>
	                  <td class = 'c1'><a href='trottinette.php?ID=".$trip["scooterID"]."'>".$trip["scooterID"]."</a></td>
	                  <td class = 'c2'>".$trip["starttime"]."</td>
	                  <td class = 'c3'>".$trip["duration"]."</td>
	                  <td class = 'c4'>".$trip["price"]." €</td>
	                  <td><button onclick='showOnMap(".$trip["sourceX"].",".$trip["sourceY"].",".$trip["destinationX"].",".$trip["destinationY"].")'>Show path on map</button></td>
	                  </tr>";
	        }
	        ?>
			</tbody>
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
			var start = L.icon({
				iconUrl: "images/marker-yellow.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});
			var finish = L.icon({
				iconUrl: "images/marker-green.png",
				iconSize: [40,40],
				iconAnchor: [20,40]
			});
	    var sourceMarker = L.marker([0,0],{icon:start}).addTo(map);
	    var destinationMarker = L.marker([0,0],{icon:finish}).addTo(map);
	    var path = L.polyline([[0,0],[0,0]],{color: 'red'}).addTo(map);

	    function showOnMap(sourceX,sourceY,destinationX,destinationY) {
	      sourceMarker.setLatLng([sourceX,sourceY]);
	      destinationMarker.setLatLng([destinationX,destinationY]);
	      path.setLatLngs([[sourceX,sourceY],[destinationX,destinationY]]);
	      map.fitBounds(path.getBounds());
				toggleModal();
	    }
			window.addEventListener("click", windowOnClick);
	  </script>
	</body>
</html>
