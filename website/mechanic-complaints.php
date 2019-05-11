<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - Complaints</title>
		<style>
		#scrolltable {margin-left: 5px; margin-top: 35px; height: 430px; overflow: auto; }
		#scrolltable table { border-collapse: collapse; width: 100%; text-align: left;}
		#scrolltable tr:nth-child(even) { background: #EEE; }
		#scrolltable th div { position: absolute; margin-top: -25px; cursor: pointer; }
		.c1 {width: 120px;}
		.c2 {width: 130px;}
		.c3 {width: 150px;}
		.c4 {width: 30px;}
		.c4 {width: 100px;}
		</style>
	</head>
	<body>
		<?php include("header.html")?>
		<br><br><br>
		<form action="mechanic-complaints.php" method="GET">
			<input type="text" name="scooterID" placeholder="Enter Scooter ID" value="<?php if (isset($_GET["scooterID"]) && !empty('$_GET["scooterID"]')) echo $_GET["scooterID"] ?>" required>
			<input type="submit" value="Search">
		</form>
		<div id="scrolltable">
			<table>
				<thead>
					  <tr>
					  <th><div>Complaint N°</div></th>
					  <th onclick="window.location = 'mechanic-complaints.php?scooterID=<?php echo $_GET["scooterID"]?>&sortBy=date'"><div>Date</div></th>
					  <th onclick="window.location = 'mechanic-complaints.php?scooterID=<?php echo $_GET["scooterID"]?>&sortBy=userID'"><div>Introduced by</div></th>
					  <th onclick="window.location = 'mechanic-complaints.php?scooterID=<?php echo $_GET["scooterID"]?>&sortBy=state'"><div>State</div></th>
					  <th onclick="window.location = 'mechanic-complaints.php?scooterID=<?php echo $_GET["scooterID"]?>&sortBy=description'"><div>Description</div></th>
					  </tr>
				</thead>
				<tbody>
				<?php
					if (isset($_GET["scooterID"]) && !empty('$_GET["scooterID"]')) {
						if (isset($_GET["sortBy"]) && !empty('$_GET["sortBy"]'))
							$complaints = getScooterComplains($_GET["scooterID"],$_GET["sortBy"]);
						else
						    $complaints = getScooterComplains($_GET["scooterID"],'date');
						$it = 0;
						foreach ($complaints as $complaint) {
							$it++;
							echo "  <tr>
									<th class = 'c1'>".$it."</th>
									<th class = 'c2'>".$complaint["date"]."</th>
									<th class = 'c3'>".$complaint["userID"]."</th>
									<th class = 'c4'>".$complaint["state"]."</th>
									<th class = 'c5'>".$complaint["description"]."</th>
									</tr>";
						}
					}
				?>
			</tbody>
		</table>
	</div>
	</body>
</html>
