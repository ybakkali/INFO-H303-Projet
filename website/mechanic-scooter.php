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
    if (isset($_GET["ID"]) && !empty('$_GET["ID"]'))
      removeScooter($_GET["ID"]);
  ?>

  <script>
    //window.history.pushState('', 'DataBase Project - Scooter', 'mechanic-scooter.php');
    function deleteScooter(id) {
      if (confirm("Do you really want to delete the scooter "+id+" ?"))
        window.location = "mechanic-scooter.php?ID="+id;
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
          <th>Complain State</th>
          <th>Battery Level</th>
          <th>Location</th>
          <th>Last Time Used</th>
          <th>Availability</th>
          <th colspan="2">Options</th>
        </tr>
        <?php
        $scooters = getAllScootersInfo();
        foreach ($scooters as $scooter) {
            echo "<tr>
                  <td>".$scooter["scooterID"]."</td>
                  <td>".$scooter["commissioningDate"]."</td>
                  <td>".$scooter["modelNumber"]."</td>
                  <td>".$scooter["complainState"]."</td>
                  <td>".$scooter["batteryLevel"]."</td>
                  <td>".$scooter["locationX"].", ".$scooter["locationY"]."</td>
                  <td>".$scooter["lastLocationTime"]."</td>
                  <td>".$scooter["availability"]."</td>
                  <td><button onclick='deleteScooter(".$scooter["scooterID"].")'>Delete</button></td>
                  <td>Modify</td>
                  </tr>";
        }
        ?>
      </table>
    </div>
</body>
</html>
