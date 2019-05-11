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
		<title>DataBase Project - Users</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Users</h1>
				<table class="w3-table w3-centered w3-responsive">
					<tr>
						<th>ID</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Phone</th>
						<th>Bank Account</th>
						<th>Address</th>
					</tr>
					<?php
						$users = getAllUsersInfo();
						foreach ($users as $user) {
							echo 		"<tr>
												<td>".$user["ID"]."</td>";
							if (isset($user["lastname"])) {
								echo 	" <td>".$user["lastname"]."</td>
												<td>".$user["firstname"]."</td>
												<td>".$user["phone"]."</td>
												<td>".$user["bankaccount"]."</td>
												<td>".$user["street"].", ".$user["number"]."<br>".$user["cp"]." ".$user["city"]."</td>";
											}
							else {
								echo 	" <td></td>
												<td></td>
												<td></td>
												<td>Upgrade</td>
												<td></td>";
							}
							echo 		"</tr>";
						}
					?>
				</table>
			</div>
		</header>
	</body>
</html>
