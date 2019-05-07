<?php
//do "sudo apt-get install php-mysql" before using

/*


$host='localhost';
$db = 'scooterDB';
$username = 'root';
$password = '';


try {
      $link = new PDO("mysql:host=$host;dbname=$db", $username, $password);
      $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo 'Connection to database was successful.';
      echo "\n";
      $req = 'SELECT scooterID, locationX, locationY FROM SCOOTERS WHERE availability = 1';
      $q = $link->query($req);
      $q->setFetchMode(PDO::FETCH_ASSOC);
}
catch(PDOException $error)
{
      echo 'ERROR: (Connection to database was unsuccessful !) : ';
      echo $error->getMessage();
      echo "\n";
}*/

// Parameters
$host = "127.0.0.1";
$username = "root";
$password = "";
$db = "scooterDB";

// Create connection
$link = mysqli_connect($host, $username, $password, $db);

// Verify connection
if (!$link) {
    die("ERROR: (Connection to database was unsuccessful!) : " . mysqli_connect_error());
    echo "\n";
}
//echo "Connection to database was successful. \n";

//example
/*
$req = "SELECT scooterID FROM SCOOTERS";
$result = mysqli_query($link, $req);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "scooterID: " . $row["scooterID"].  "<br>";
    }
}
else {
    echo "There isn't any result for this request.";
}
*/


function getRegisteredID() {
    $last_ID_req = "SELECT max(`ID`)
                    FROM `ALL_USERS`
                    WHERE `lastname` IS NOT NULL";
    $result = mysqli_query($GLOBALS['link'], $last_ID_req);
    if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
    }
    return $data["max(`ID`)"] + 1;
}


function getUnregisteredID() {
    $last_ID_req = "SELECT max(`ID`)
                    FROM `ALL_USERS`
                    WHERE `lastname` IS NULL";
    $result = mysqli_query($GLOBALS['link'], $last_ID_req);
    if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
    }
    return $data["max(`ID`)"] + 1;
}


function addUnregiteredUser($password, $bankaccount) {
    $ID = getUnregisteredID();
    //echo "Your ID is : ", $ID, "\n";
    $adding = "INSERT INTO `ALL_USERS` (ID, password, bankaccount)
               VALUES ($ID, '$password', '$bankaccount')";
    if (!(mysqli_query($GLOBALS['link'], $adding))) {
        echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
    }
    return $ID; // To display for user
}


function addRegiteredUser($password, $bankaccount, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber) {
    $ID = getRegisteredID();
    //echo "Your ID is : ", $ID, "\n";
    $adding = "INSERT INTO `ALL_USERS` (ID, password, bankaccount, lastname, firstname, phone)
               VALUES ($ID, '$password', '$bankaccount', '$lastname', '$firstname', '$phone')";
    if (!(mysqli_query($GLOBALS['link'], $adding))) {
        echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']),"\n";
    }
    $adding_adrs = "INSERT INTO `USER_ADDRESS` (ID, city, cp, street, number)
                    VALUES ($ID, '$adrsCity', $adrsZip, '$adrsStreet', $adrsNumber)";
    if (!(mysqli_query($GLOBALS['link'], $adding_adrs))) {
        echo "Error : (could not insert new data !) : " . $adding_adrs . "<br>" . mysqli_error($GLOBALS['link']),"\n";
    }
    return $ID; // To display for user
}


function AveScooterPos() { //consulter les trottinettes disponibles et leur localisation
  $avScotPos_req = "SELECT `scooterID`, `locationX`, `locationY`
                    FROM `SCOOTERS`
                    WHERE `availability` = 1";
  $result = $GLOBALS['link']->query($avScotPos_req);

  $items = array();
  $count = 0;

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $items[$count] = ($row);
          $count++;
          //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
      }
  }
  else {
      echo "Didn't find any results.\n";
  }
  return $items;
}



function userTripsHistory($uid) { //consulter l'historique des déplacement eectués
    $history_req = "SELECT *
           FROM `TRIPS`
           WHERE `userID` = $uid
           ORDER BY `endtime`";

    $result = $GLOBALS['link']->query($history_req);

    $items = array();
    $count = 0;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[$count] = ($row);
            $count++;
            //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
        }
        print_r($items);
    }
    else {
        echo "Didn't find any results.\n";
    }
    return $items;
}

userTripsHistory(500);
mysqli_close($link);
?>