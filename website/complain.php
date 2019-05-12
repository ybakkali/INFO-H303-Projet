<!DOCTYPE html>

<?php
	include("global.php");
	include("../manager.php");
	if (!isloggedIn()){
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}

	$ID = $description = "";
	if (isset($_GET['ID'])) $ID = $_GET["ID"];
	$IDErr = $descriptionErr = "";
	$ready = false;

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

	//Description
		if (empty('$_POST["description"]')) {
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
			echo "<script>
							alert('Complaint successfully sent');
							window.location = 'trottinette.php';
						</script>";
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - Complain</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Complain</h1>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label>ID</label><br>
					<input type="text" name="ID" placeholder="Enter Scooter ID" value="<?php echo $ID ?>" required><br>
					<p class="w3-text-red"><?php echo $IDErr;?></p>

					<label>Description</label><br>
					<textarea name="description" placeholder="Enter Description" cols="40" rows="5" required><?php echo $description ?></textarea><br>
					<p class="w3-text-red"><?php echo $descriptionErr;?></p>

					<input type="submit" name="submit" value="Submit">
				</form>
			</div>
		</header>
	</body>
</html>
