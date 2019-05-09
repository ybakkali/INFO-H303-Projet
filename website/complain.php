<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Complain </title>
	<?php
		include("global.php");
		session_start();
		include("header.php");
		include("../manager.php");
	?>

	<?php
		$ID = $description = "";
		if (isset($_GET['ID'])) $ID = $_GET["ID"];
		$IDErr = $descriptionErr = "";
		$ready = false;
		$result = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
		// ID
			$ready = true;
			if (empty($_POST["ID"])) {
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

		//Description
			if (empty($_POST["description"])) {
					$descriptionErr = "Description is required";
					$ready = false;
			}
			else {
				$description = test_input($_POST["description"]);
				// check if description only contains letters and numbers
				$ready = $ready && true;
			}
			if ($ready) {
				complainScooter($ID,$_SESSION["ID"],$description);
				$result = "<h2 style = 'color:green; position:relative; top:-75px'>Complaint successfully sent</h2>";
			}
		}
	?>

	<h1 style="padding:100px">Complain</h1>
	<?php echo $result ?>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<h3 style = "position:relative; top:-75px">ID<br><input type="text" name="ID" value="<?php echo $ID ?>" required>
		<br><span class="error"><font color="red"><?php echo $IDErr;?></font></span>
		<br><br>
		Description<br><textarea name="description" cols="40" rows="5" required><?php echo $description ?></textarea>
		<br><span class="error"><font color="red"><?php echo $descriptionErr;?></font></span>
		<br><br>
		<input type="submit" name="submit" value="Submit"> </h3>
	</form>



</body>
</html>
