<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - Menu</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Menu</h1>
				<a href="mechanic-scooter.php?sortBy=scooterID" class="w3-button">Manage scooters</a><br>
				<a href="mechanic-complaints.php" class="w3-button">Manage complaints</a><br>
				<a href="mechanic-user.php" class="w3-button">Manage users</a><br>
				<a href="mechanic-request.php" class="w3-button">Show request</a>
			</div>
		</header>
	</body>
</html>
