<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Register </title>
	<?php include("global.php");
			session_start();
			include("header.php");
			include("../manager.php");
	?>

<?php
if (isloggedIn()) echo "<script>window.location = 'menu.php';</script>";
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
	if (empty($_POST["bankAccount"])) {
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

//Verify Password
	if (empty($_POST["passwordVerif"])) {
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
		if (empty($_POST["firstName"])) {
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
		if (empty($_POST["lastName"])) {
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
		if (empty($_POST["phone"])) {
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
		if (empty($_POST["addrNumber"])) {
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
		if (empty($_POST["addrStreet"])) {
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
		if (empty($_POST["addrCity"])) {
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
		if (empty($_POST["addrPostal"])) {
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

?>

<?php if ($charger == "No") echo"<style> #conditional_part {display:none;} </style>";?>

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

<h1 style="padding:100px">Register</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	<h3 style = "position:relative; top:-75px">Bank Account<br><input type="text" name="bankAccount" value="<?php echo $bankAccount?>" required>
	<br><span class="error"><font color="red"><?php echo $bankAccountErr;?></font></span>
	<br><br>

	Password<br><input type="password" name="password" required>
	<br><span class="error"><font color="red"><?php echo $passwordErr;?></font></span>
	<br><br>

	Verify Password<br><input type="password" name="passwordVerif" required>
	<br><span class="error"><font color="red"><?php echo $passwordVerifErr;?></font></span>
	<br><br>

	Charger? : <input id="charger" type="checkbox" name="charger" style="width:30px;height:30px" <?php if ($charger == "Yes") echo "checked" ?>></h3>
	<br><br>

	<div id="conditional_part">
		<h3 style = "position:relative; top:-100px">First Name<br><input type="text" name="firstName" value="<?php echo $firstName;?>">
		<br><span class="error"><font color="red"><?php echo $firstNameErr;?></font></span>
		<br><br>

		Last Name<br><input type="text" name="lastName" value="<?php echo $lastName;?>">
		<br><span class="error"><font color="red"><?php echo $lastNameErr;?></font></span>
		<br><br>

		Phone<br><input type="text" name="phone" value="<?php echo $phone;?>">
		<br><span class="error"><font color="red"><?php echo $phoneErr;?></font></span></h3>
		<br><br>

		<h2 style = "position:relative; top:-125px">Address</h2>

		<h4 style = "position:relative; top:-75px">Number<br><input type="text" name="addrNumber" value="<?php echo $addrNumber;?>">
		<br><span class="error"><font color="red"><?php echo $addrNumberErr;?></font></span>
		<br><br>

		Street<br><input type="text" name="addrStreet" value="<?php echo $addrStreet;?>">
		<br><span class="error"><font color="red"><?php echo $addrStreetErr;?></font></span>
		<br><br>

		City<br><input type="text" name="addrCity" value="<?php echo $addrCity;?>">
		<br><span class="error"><font color="red"><?php echo $addrCityErr;?></font></span>
		<br><br>

		Postal Code<br><input type="text" name="addrPostal" value="<?php echo $addrPostal;?>">
		<br><span class="error"><font color="red"><?php echo $addrPostalErr;?></font></span>
		<br><br></h4>
	</div>
	<h4 style = "position:relative; top:-75px"><input type="submit" name="submit" value="Submit"></h4>
</form>

</body>
</html>
