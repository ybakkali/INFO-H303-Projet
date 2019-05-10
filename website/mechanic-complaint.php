<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Complaint </title>
	<?php
		include("global.php");
		session_start();
		include("header.php");
		include("../manager.php");
		if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) echo "<script>window.location = 'home.php';</script>";
	?>

	<h1 style="padding:100px">Complaint</h1>
	<div style = "position:relative; top:-75px">
		<form action="mechanic-complaint.php" method="GET">
			<input type="text" name="ID" placeholder="Enter Scooter ID" value="<?php if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) echo $_GET["ID"] ?>" required>
			<input type="submit" value="Search">
		</form>
	</div>

	<?php
		if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
			$complains = getScooterComplains($_GET["ID"]);
		}
		else exit();
	?>

</body>
</html>
