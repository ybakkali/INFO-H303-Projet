<!DOCTYPE html>

<?php
	include("global.php");
	include("../manager.php");
	if (isloggedIn()){
		echo "<script>window.location = 'menu.php';</script>";
		exit();
	}

	// For all users
	$bankAccount = $password = $passwordVerif = "";
	$bankAccountErr = $passwordErr = $passwordVerifErr = "";

	// For recharger users
	$firstName = $lastName = $phone = $addrNumber = $addrStreet = $addrCity = $addrPostal = "";
	$firstNameErr = $lastNameErr = $phoneErr = $addrNumberErr = $addrStreetErr = $addrCityErr = $addrPostalErr = "";

	$charger = "No";

	$ready = false;

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$ready = true;

	// Bank Account
		if (empty('$_POST["bankAccount"]')) {
			$bankAccountErr = "Bank Account is required";
			$ready = false;
		}
		else {
			$bankAccount = test_input($_POST["bankAccount"]);
			// check if bankAccount only contains numbers
			if (preg_match("/[^0-9]/",$bankAccount)) {
				$bankAccountErr = "Only numbers allowed";
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

	//Verify Password
		if (empty('$_POST["passwordVerif"]')) {
				$passwordVerifErr = "Verify Password is required";
				$ready = false;
		}
		else {
			$passwordVerif = test_input($_POST["passwordVerif"]);
			// check if passwordVerif match password
			if ($passwordVerif != $password) {
				$passwordVerifErr = "Passwords don't match";
				$ready = false;
			}
			else {$ready = $ready && true;}
		}

		if (!isset($_POST["charger"])) { // not Charger
			$charger = "No";
			if ($ready) {
					if (addUnregiteredUser($password,$bankAccount)) {
							echo "<script>
									alert('Successful Registration\\n\\nYour ID is ".$_SESSION["ID"]."\\n\\nRemember it!')
									window.location = 'menu.php';
							</script>";
					}
					else {
							echo "<script>
									alert('Unsuccessful Registration\\n\\nPlease try again')
									window.location = 'menu.php';
								</script>";
					}
			}
		}
		else { // Charger
			$charger = "Yes";
		//First Name
			if (empty('$_POST["firstName"]')) {
					$firstNameErr = "First Name is required";
					$ready = false;
			}
			else {
				$firstName = test_input($_POST["firstName"]);
				// check if firstName only contains letters
				if (preg_match("/[^A-Za-z]/",$firstName)) {
					$firstNameErr = "Only letters allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		//Last Name
			if (empty('$_POST["lastName"]')) {
					$lastNameErr = "Last Name is required";
					$ready = false;
			}
			else {
				$lastName = test_input($_POST["lastName"]);
				// check if lastName only contains letters
				if (preg_match("/[^A-Za-z]/",$lastName)) {
					$lastNameErr = "Only letters allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		// Phone
			if (empty('$_POST["phone"]')) {
				$phoneErr = "Phone is required";
				$ready = false;
			}
			else {
				$phone = test_input($_POST["phone"]);
				// check if phone only contains numbers
				if (preg_match("/[^0-9]/",$phone)) {
					$phoneErr = "Only numbers allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		// AddrNumber
			if (empty('$_POST["addrNumber"]')) {
				$addrNumberErr = "Number is required";
				$ready = false;
			}
			else {
				$addrNumber = test_input($_POST["addrNumber"]);
				// check if addrNumber only contains numbers
				if (preg_match("/[^A-Za-z0-9]/",$addrNumber)) {
					$addrNumberErr = "Only numbers allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		// AddrStreet
			if (empty('$_POST["addrStreet"]')) {
				$addrStreetErr = "Street is required";
				$ready = false;
			}
			else {
				$addrStreet = test_input($_POST["addrStreet"]);
				// check if addrStreet only contains letters
				if (preg_match("/[^A-Za-z]/",$addrStreet)) {
					$addrStreetErr = "Only letters allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		// AddrCity
			if (empty('$_POST["addrCity"]')) {
				$addrCityErr = "City is required";
				$ready = false;
			}
			else {
				$addrCity = test_input($_POST["addrCity"]);
				// check if addrCity only contains letters
				if (preg_match("/[^A-Za-z]/",$addrCity)) {
					$addrCityErr = "Only letters allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}

		// AddrPostal
			if (empty('$_POST["addrPostal"]')) {
				$addrPostalErr = "Postal Code is required";
				$ready = false;
			}
			else {
				$addrPostal = test_input($_POST["addrPostal"]);
				// check if addrPostal only contains numbers
				if (preg_match("/[^0-9]/",$addrPostal)) {
					$addrPostalErr = "Only numbers allowed";
					$ready = false;
				}
				else {$ready = $ready && true;}
			}
			if ($ready) {
				if (addRegiteredUser($password, $bankAccount, $lastName, $firstName, $phone, $addrCity, $addrPostal, $addrStreet, $addrNumber)) {
						echo "<script>
								alert('Successful Registration\\n\\nYour ID is ".$_SESSION["ID"]."\\n\\nRemember it!')
								window.location = 'menu.php';
						</script>";
				}
				else {
						echo "<script>
								alert('Unsuccessful Registration\\n\\nPlease try again')
								window.location = 'menu.php';
							</script>";
				}
			}
		}
	}
if ($charger == "No") echo"<style> #conditional_part {display:none;} </style>";
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - Register</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">Register</h1>

				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

					<label>Bank Account</label><br>
					<input type="text" name="bankAccount" value="<?php echo $bankAccount?>" required><br>
					<p class="w3-text-red"><?php echo $bankAccountErr;?></p>

					<label>Password</label><br>
					<input type="password" name="password" required><br>
					<p class="w3-text-red"><?php echo $passwordErr;?></p>

					<label>Verify Password</label><br>
					<input type="password" name="passwordVerif" required><br>
					<p class="w3-text-red"><?php echo $passwordVerifErr;?></p>

					<label>Charger? : </label>
					<input id="charger" type="checkbox" name="charger" style="width:30px;height:30px" <?php if ($charger == "Yes") echo "checked" ?>><br>

					<div id="conditional_part"><br>
						<label>First Name</label><br>
						<input type="text" name="firstName" value="<?php echo $firstName;?>"><br>
						<p class="w3-text-red"><?php echo $firstNameErr;?></p>

						<label>Last Name</label><br>
						<input type="text" name="lastName" value="<?php echo $lastName;?>"><br>
						<p class="w3-text-red"><?php echo $lastNameErr;?></p>

						<label>Phone</label><br>
						<input type="text" name="phone" value="<?php echo $phone;?>"><br>
						<p class="w3-text-red"><?php echo $phoneErr;?></p>

						<label>Address</label><br>

						<div class="w3-xlarge">
							<label>Number</label><br>
							<input type="text" name="addrNumber" value="<?php echo $addrNumber;?>"><br>
							<p class="w3-text-red"><?php echo $addrNumberErr;?></p>

							<label>Street</label><br>
							<input type="text" name="addrStreet" value="<?php echo $addrStreet;?>"><br>
							<p class="w3-text-red"><?php echo $addrStreetErr;?></p>

							<label>City</label><br>
							<input type="text" name="addrCity" value="<?php echo $addrCity;?>"><br>
							<p class="w3-text-red"><?php echo $addrCityErr;?></p>

							<label>Postal Code</label><br>
							<input type="text" name="addrPostal" value="<?php echo $addrPostal;?>"><br>
							<p class="w3-text-red"><?php echo $addrPostalErr;?></p>
						</div>
					</div>
					<br>
					<input type="submit" name="submit" value="Submit">
				</form>
			</div>
		</header>
	</body>
</html>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$('#charger').change(function(){
			if(this.checked) {
				$charger = "Yes";
				$('#conditional_part').fadeIn();
			}
			else {
				$charger = "No";
				$('#conditional_part').fadeOut();
			}
		});
	});

</script>
