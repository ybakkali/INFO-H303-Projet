<!DOCTYPE html>

<?php
	include("global.php");
	include("../manager.php");
	if (!isloggedIn()){
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}

	$ID = $note = "";
	if (isset($_GET['ID'])) $ID = $_GET["ID"];
	$IDErr = $noteErr = "";
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

	//note
		$note = test_input($_POST["note"]);
		// check if note only contains letters and numbers

		if ($ready) {
			if ($_POST["state"] == "traited")
				reparationScooter($ID,$_POST["userID"],$_SESSION["ID"],$_POST["date"],$note);
			else
				updateComplaintState($_POST["userID"], $ID, $_POST["date"], $_POST["state"]);
			echo "<script>window.location = 'mechanic-complaints.php?scooterID=".$ID."';</script>";
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
				<h1 class="w3-jumbo">Reparation</h1>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label>Scooter ID</label><br>
					<input type="text" name="ID" placeholder="Enter Scooter ID" value="<?php echo $ID ?>" required><br>
					<p class="w3-text-red"><?php echo $IDErr;?></p>
					<select name="state">
					  <option value="inProcess">In Process</option>
					  <option value="traited">Traited</option>
					</select>
					<br>
					<input type="hidden"  name="date" value="<?php echo $_GET["date"] ?>">
					<input type="hidden"  name="userID" value="<?php echo $_GET["userID"] ?>">
					<label>Note</label><br>
					<textarea name="note" placeholder="Enter Note" cols="40" rows="5"><?php echo $note ?></textarea><br>
					<p class="w3-text-red"><?php echo $noteErr;?></p>

					<input type="submit" name="submit" value="Submit">
				</form>
			</div>
		</header>
	</body>
</html>
