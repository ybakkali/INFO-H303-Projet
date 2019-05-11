<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!isloggedIn()) {
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
				<a href="map.php" class="w3-button">Check the available scooters</a><br>
				<a href="trottinette.php" class="w3-button">Search a specific scooter</a><br>
				<a href="history.php?sortBy=starttime" class="w3-button">Check your trips history</a>
				<?php //if ($_SESSION["type"] != "unregistered") echo "<input type=\"submit\" value=\"Check your trips history\" onclick=\"window.location='history.php';\">" ?>
			</div>
		</header>
	</body>
</html>
