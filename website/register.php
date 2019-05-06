<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Register </title>
	<?php include("header.html");?>

<h2>Register</h2>

<?php

// For all users
$username = $bankAccount = $password = $passwordVerif = "";
$usernameErr = $bankAccountErr = $passwordErr = $passwordVerifErr = "";

// For recharger users
$firstName = $lastName = $phone = $addrNumber = $addrStreet = $addrCity = $addrZip = "";
$firstNameErr = $lastNameErr = $phoneErr = $addrNumberErr = $addrStreetErr = $addrCityErr = $addrZipErr = "";

$charger = "No";

$ready = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$ready = true;

// Username
	if (empty($_POST["username"])) {
		$usernameErr = "Userame is required";
		$ready = false;
	}
	else {
		$username = test_input($_POST["username"]);
		// check if username only contains letters and numbers
		if (preg_match("/[^A-Za-z0-9]/",$username)) {
			$usernameErr = "Only letters and numbers allowed";
			$ready = false;
		}
		else {$ready = $ready && true;}
	}

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
			$phoneErr = "Userame is required";
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

	// AddrZip
		if (empty($_POST["addrZip"])) {
			$addrZipErr = "Zip Code is required";
			$ready = false;
		}
		else {
			$addrZip = test_input($_POST["addrZip"]);
			// check if addrZip only contains numbers
			if (preg_match("/[^0-9]/",$addrZip)) {
				$addrZipErr = "Only numbers allowed";
				$ready = false;
			}
			else {$ready = $ready && true;}
		}
	}

}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	<h3>Username : <input type="text" name="username" value="<?php echo $username;?>">
	<span class="error"><font color="red"><?php echo $usernameErr;?></font></span>
	<br><br>

	Bank Account : <input type="text" name="bankAccount" value="<?php echo $bankAccount;?>">
	<span class="error"><font color="red"><?php echo $bankAccountErr;?></font></span>
	<br><br>

	Password : <input type="password" name="password">
	<span class="error"><font color="red"><?php echo $passwordErr;?></font></span>
	<br><br>

	Verify Password : <input type="password" name="passwordVerif">
	<span class="error"><font color="red"><?php echo $passwordVerifErr;?></font></span>
	<br><br>

	Charger? : <input id="charger" type="checkbox" name="charger" <?php if ($charger == "Yes") echo "checked" ?>>
	<br><br>

	<div id="conditional_part">
		First Name : <input type="text" name="firstName" value="<?php echo $firstName;?>">
		<span class="error"><font color="red"><?php echo $firstNameErr;?></font></span>
		<br><br>

		Last Name : <input type="text" name="lastName" value="<?php echo $lastName;?>">
		<span class="error"><font color="red"><?php echo $lastNameErr;?></font></span>
		<br><br>

		Phone : <input type="text" name="phone" value="<?php echo $phone;?>">
		<span class="error"><font color="red"><?php echo $phoneErr;?></font></span>
		<br><br>

		Address :

		<p>Number : <input type="text" name="addrNumber" value="<?php echo $addrNumber;?>">
		<span class="error"><font color="red"><?php echo $addrNumberErr;?></font></span>
		<br><br>

		Street : <input type="text" name="addrStreet" value="<?php echo $addrStreet;?>">
		<span class="error"><font color="red"><?php echo $addrStreetErr;?></font></span>
		<br><br>

		City : <input type="text" name="addrCity" value="<?php echo $addrCity;?>">
		<span class="error"><font color="red"><?php echo $addrCityErr;?></font></span>
		<br><br>

		Zip Code : <input type="text" name="addrZip" value="<?php echo $addrZip;?>">
		<span class="error"><font color="red"><?php echo $addrZipErr;?></font></span>
		<br><br></p>
	</div>
	<input type="submit" name="submit" value="Submit"></h3>
</form>

<a href="about">Back</a>
<?php include("footer.html");?>
</body>
</html>
