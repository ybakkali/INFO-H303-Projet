<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - History </title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
	integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
	integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
	crossorigin=""></script>

  <?php
    include("global.php");
		session_start();
		include("header.php");
    include("../manager.php");
		if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
	?>
	<script src="popup.js"></script>
	<h1 style="padding:100px">Scooter</h1>
    <div style = "position:relative; top:-75px; text-align: center; overflow: auto; height: 65%;">
      <table style = " position:relative; margin-left: auto; margin-right: auto; width: 75%; text-align: center;">
        <col width="5%">
        <col>
        <col>
        <col>
        <col>

        <tr>
          <th>Scooter ID</th>
          <th>Time</th>
          <th>Duration</th>
          <th>Price</th>
          <th></th>
        </tr>
        <?php
        $trips = userTripsHistory($_SESSION["ID"]);
        foreach ($trips as $trip) {
            echo "<tr>
                  <td><a href='trottinette.php?ID=".$trip["scooterID"]."'>".$trip["scooterID"]."</a></td>
                  <td>".$trip["starttime"]."</td>
                  <td>".$trip["duration"]."</td>
                  <td>".$trip["price"]." €</td>
                  <td><button onclick='showOnMap(".$trip["sourceX"].",".$trip["sourceY"].",".$trip["destinationX"].",".$trip["destinationY"].")'>Show path on map</button></td>
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
  </script>
</body>
</html>