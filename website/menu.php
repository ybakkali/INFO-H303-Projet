<html>
<title>DataBase Project</title>
<body>
	<h1>Trottinettes</h1>
	Best website ever!

<h1>Menu</h1>

<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
	exit();
}

	$username = $_SESSION['username'];
?>

<?php echo $username;?>

</body>
</html>
