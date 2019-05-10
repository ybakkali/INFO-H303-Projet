<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - User </title>
	<?php include("global.php");
				session_start();
				include("header.php");
				if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) echo "<script>window.location = 'home.php';</script>";
	?>
	<h1 style="padding:100px">User</h1>
	<div style = "position:relative; top:-75px">

	</div>

</body>
</html>
