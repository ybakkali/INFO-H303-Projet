<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Scooter </title>
	<?php include("global.php");
				session_start();
				include("header.php");
        include("../manager.php");
				if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) echo "<script>window.location = 'home.php';</script>";
	?>

  <?php

	  if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
		 		if (isset($_GET["delete"]) && $_GET["delete"] = 'true')
	      		removeScooter($_GET["ID"]);
				else if (isset($_GET["repair"]) && $_GET["repair"] = 'true')
						repairScooter($_GET["ID"]);
				else if (isset($_GET["fix"]) && $_GET["fix"] = 'true')
						fixScooter($_GET["ID"]);
		}
  ?>

  <script>
    window.history.pushState('', 'DataBase Project - Scooter', 'mechanic-scooter.php');
    function deleteScooter(id) {
      if (confirm("Do you really want to delete the scooter "+id+" ?"))
        window.location = "mechanic-scooter.php?ID="+id +"&delete=true";
    }

		function repairScooter(id) {
			if (confirm("Do you really want to repair the scooter "+id+" ?"))
				window.location = "mechanic-scooter.php?ID="+id +"&repair=true";
		}

		function fixScooter(id) {
			if (confirm("Is the scooter "+id+" really repaired?"))
				window.location = "mechanic-scooter.php?ID="+id +"&fix=true";
		}
  </script>

	<h1 style="padding:100px">Scooter</h1>
    <div style = "position:relative; top:-75px; text-align: center; overflow: auto; height: 65%;">
      <table style = "width: 100%; text-align: center;">
        <col width="5%">
        <col>
        <col>
        <col width="5%">
        <col width="5%">
        <col>
        <col>
        <col>
        <col>
        <col>
        <tr>
          <th>Scooter ID</th>
          <th>Commissioning Date</th>
          <th>Model Number</th>
          <th>Complains</th>
          <th>Battery Level</th>
          <th>Location</th>
          <th>Last Time Used</th>
          <th>Availability</th>
          <th colspan="2">Options</th>
        </tr>
        <?php
        $scooters = getAllScootersInfo();
        foreach ($scooters as $scooter) {
						$totalComplains = getComplainsNumber($scooter["scooterID"]);
            echo "<tr>
                  <td>".$scooter["scooterID"]."</td>
                  <td>".$scooter["commissioningDate"]."</td>
                  <td>".$scooter["modelNumber"]."</td>
                  <td>".$totalComplains."</td>
                  <td>".$scooter["batteryLevel"]."</td>
                  <td>".$scooter["locationX"].", ".$scooter["locationY"]."</td>
                  <td>".$scooter["lastLocationTime"]."</td>
                  <td>".$scooter["availability"]."</td>
                  <td><button onclick='deleteScooter(".$scooter["scooterID"].")'>Delete</button></td>
									<td><button onclick='repairScooter(".$scooter["scooterID"].")'>Repair</button></td>
									<td><button onclick='fixScooter(".$scooter["scooterID"].")'>Fix</button></td>
									</tr>";
        }
        ?>
      </table>
    </div>
</body>
</html>
