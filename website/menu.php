<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Menu </title>
	<?php include("header.html");?>
	<h1> Menu </h1>

<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
	echo "Login required";
	exit();
}

	$username = $_SESSION['username'];
	echo $username;
?>
<?php include("footer.html");?>
</body>
</html>
