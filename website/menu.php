<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Menu </title>
	<?php include("global.php");
				session_start();
				include("header.php");
				if (!isloggedIn()) echo "<script>window.location = 'home.php';</script>";
	?>
	<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
		<div class="w3-display-center w3-text-white" style="padding:250px; text-shadow: 2px 2px 4px #000000;">
			<span class="w3-jumbo w3-hide-small">You are in the menu!</span><br>
		</div>
	</header>
<?php include("footer.php");?>
</body>
</html>
