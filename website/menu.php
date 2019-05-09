<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Menu </title>
	<?php include("global.php");
				session_start();
				include("header.php");
				if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
	?>
	<a href="map.php"><h2 style="padding:250px">Check the available scooters</h2></a>
	<a href="trottinette.php"><h2 style="padding:250px">Search a specific scooter</h2></a>
	<a href="menu.php"><h2 style="padding:250px">Check your trips history</h2></a>

	<?php
		if ($_SESSION["recharger"] == "Yes") echo '<a href="menu.php"><h2 style="padding:250px">Take, recharge and bring back a scooter</h2></a>';
	?>

<?php include("footer.php");?>
</body>
</html>
