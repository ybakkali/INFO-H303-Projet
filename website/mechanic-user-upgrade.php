<!DOCTYPE html>
<?php
	include("global.php");
	include("../manager.php");
	if (!(isset($_SESSION["ID"]) && $_SESSION["type"] == "mechanic")) {
		echo "<script>window.location = 'home.php';</script>";
		exit();
	}

  $ID = $firstName = $lastName = $phone = $addrNumber = $addrStreet = $addrCity = $addrPostal = "";
  $IDErr = $firstNameErr = $lastNameErr = $phoneErr = $addrNumberErr = $addrStreetErr = $addrCityErr = $addrPostalErr = "";

  if (isset($_GET["ID"]) && !empty('$_GET["ID"]')) $ID = $_GET["ID"];

  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $ready = true;
    // ID
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
      if (convertToRegUser($ID, $lastName, $firstName, $phone, $addrCity, $addrPostal, $addrStreet, $addrNumber)) {
          echo "<script>
              alert('Successful Upgradation')
              window.location = 'mechanic-users.php';
          </script>";
      }
      else {
          echo "<script>
              alert('Unsuccessful Upgradation\\n\\nPlease try again')
            </script>";
      }
    }
  }

?>

<html>
	<head>
		<meta charset="utf-8">
		<title>DataBase Project - User Upgrade</title>
	</head>
	<body>
		<?php include("header.html")?>
		<header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
			<div class="w3-display-middle w3-text-white w3-xxlarge title">
				<h1 class="w3-jumbo">User Upgrade</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <label>User ID</label><br>
          <input type="text" name="ID" placeholder="Enter User ID" value="<?php echo $ID ?>" required><br>
          <p class="w3-text-red"><?php echo $IDErr;?></p>

          <label>First Name</label><br>
          <input type="text" name="firstName" placeholder="Enter First Name" value="<?php echo $firstName;?>" required><br>
          <p class="w3-text-red"><?php echo $firstNameErr;?></p>

          <label>Last Name</label><br>
          <input type="text" name="lastName" placeholder="Enter Last Name" value="<?php echo $lastName;?>" required><br>
          <p class="w3-text-red"><?php echo $lastNameErr;?></p>

          <label>Phone</label><br>
          <input type="text" name="phone" placeholder="Enter Phone Number" value="<?php echo $phone;?>" required><br>
          <p class="w3-text-red"><?php echo $phoneErr;?></p>

          <label>Address</label><br>

          <div class="w3-xlarge">
            <label>Number</label><br>
            <input type="text" name="addrNumber" placeholder="Enter Number" value="<?php echo $addrNumber;?>" required><br>
            <p class="w3-text-red"><?php echo $addrNumberErr;?></p>

            <label>Street</label><br>
            <input type="text" name="addrStreet" placeholder="Enter Street" value="<?php echo $addrStreet;?>" required><br>
            <p class="w3-text-red"><?php echo $addrStreetErr;?></p>

            <label>City</label><br>
            <input type="text" name="addrCity" placeholder="Enter City" value="<?php echo $addrCity;?>" required><br>
            <p class="w3-text-red"><?php echo $addrCityErr;?></p>

            <label>Postal Code</label><br>
            <input type="text" name="addrPostal" placeholder="Enter Postal Code" value="<?php echo $addrPostal;?>" required><br>
            <p class="w3-text-red"><?php echo $addrPostalErr;?></p>
          </div>
          <input type="submit" name="submit" value="Submit">
        </form>
			</div>
		</header>
	</body>
</html>
