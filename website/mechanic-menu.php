<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Menu </title>
	<?php include("global.php");
				session_start();
				include("header.php");
				if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) echo "<script>window.location = 'home.php';</script>";
	?>
	<h1 style="padding:100px">Menu</h1>
	<div style = "position:relative; top:-75px">
		<a href="mechanic-scooter.php"><h2>Manage scooters</h2></a>
		<a href="mechanic-complaint.php"><h2>Manage complaints</h2></a>
		<a href="mechanic-user.php"><h2>Manage users</h2></a>
		<a href="mechanic-request.php"><h2>Show request</h2></a>
	</div>

</body>
</html>
