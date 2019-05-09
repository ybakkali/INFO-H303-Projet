<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Login </title>
	<?php
		include("global.php");
		session_start();
		include("header.php");
		include("../manager.php");
	?>

<?php
if (isloggedIn()) echo "<script>window.location = 'menu.php';</script>";
$ID = $password = "";
$IDErr = $passwordErr = "";

$ready = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// ID
	$ready = true;
	if (empty($_POST["ID"]) || $_POST["ID"] == "0") {
		$IDErr = "ID is required";
		$ready = false;
	}
	else {
		$ID = test_input($_POST["ID"]);
		// check if ID only contains letters and numbers
		if (preg_match("/[^0-9]/",$ID)) {
			$IDErr = "Only numbers allowed";
			$ready = false;
		}
		else {$ready = $ready && true;}
	}

//Password
	if (empty($_POST["password"])) {
			$passwordErr = "Password is required";
			$ready = false;
	}
	else {
		$password = test_input($_POST["password"]);
		// check if password only contains letters and numbers
		if (preg_match("/[^A-Za-z0-9]/",$password)) {
			$passwordErr = "Only letters and numbers allowed";
			$ready = false;
		}
		else {$ready = $ready && true;}
	}
	if ($ready) {
		if (userAuthentication($ID,$password))
			echo "<script>window.location = 'menu.php'</script>";
		else
			echo "Error";
	}
}

?>


<h1 style="padding:100px">Login</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<h3 style = "position:relative; top:-75px">ID<br><input type="text" name="ID" value="<?php echo $ID ?>" required>
	<br><span class="error"><font color="red"><?php echo $IDErr;?></font></span>
	<br><br>
	Password<br><input type="password" name="password" required>
	<br><span class="error"><font color="red"><?php echo $passwordErr;?></font></span>
	<br><br>
	<input type="submit" name="submit" value="Submit"> </h3>
</form>

</body>
</html>
