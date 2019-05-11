<!DOCTYPE html>

<?php
	include("global.php");
	include("../manager.php");
	if (isloggedIn()){
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
	$ID = $password = "";
	$IDErr = $passwordErr = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	// ID
		$ready = true;
		if (empty('$_POST["ID"]')) {
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
		if (empty('$_POST["password"]')) {
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

<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - Login</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Login</h1>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label>ID</label><br>
					<input type="text" name="ID" placeholder="Enter ID" value="<?php echo $ID ?>" required><br>
					<p class="w3-text-red"><?php echo $IDErr;?></p>

					<label>Password</label><br>
					<input type="password" name="password" placeholder="Enter Password" required><br>
					<p class="w3-text-red"><?php echo $passwordErr;?></p>

					<input type="submit" name="submit" value="Submit">
				</form>
			</div>
		</header>
	</body>
</html>
