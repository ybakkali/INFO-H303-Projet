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
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Complaints</h1>
				<form action="mechanic-complaints.php" method="GET">
					<input type="text" name="ID" placeholder="Enter Scooter ID" value="<?php if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) echo $_GET["ID"] ?>" required>
					<input type="submit" value="Search">
				</form>
				<br>
				<table class="w3-table w3-auto w3-centered w3-responsive">
					<tr>
						<th>User ID</th>
						<th>Date</th>
						<th>Description</th>
					</tr>
					<?php
						if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) {
							$complains = getScooterComplains($_GET["ID"]);
							foreach ($complains as $complain) {
								echo "<tr>
												<td>".$complain["userID"]."</td>
												<td>".$complain["date"]."</td>
												<td>".$complain["description"]."</td>
											</tr>";
							}
						}
					?>
				</table>
			</div>
		</header>
	</body>
</html>
