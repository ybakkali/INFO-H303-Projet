<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Menu </title>
	<?php include("global.php");
				session_start();
				include("header.php");
				if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
	?>
	<h1 style="padding:100px">Menu</h1>
	<div style = "position:relative; top:-75px">
		<a href="map.php"><h2>Check the available scooters</h2></a>
		<a href="trottinette.php"><h2>Search a specific scooter</h2></a>
		<a href="history.php"><h2>Check your trips history</h2></a>
	<?php
		if ($_SESSION["type"] != "unregistered") echo '<a href="menu.php"><h2>Take, recharge and bring back a scooter</h2></a>';
	?>
	</div>

</body>
</html>
