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


function addUnregiteredUser($password, $bankaccount) { //inscrire un nouvel utilisateur (avec ou sans droit de recharge des trottinettes)
    $ID = getUnregisteredID();
    //echo "Your ID is : ", $ID, "\n";
    $adding = "INSERT INTO `ALL_USERS` (ID, password, bankaccount)
               VALUES ($ID, '$password', '$bankaccount')";
    if (!(mysqli_query($GLOBALS['link'], $adding))) {
        echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
    }
    return $ID; // To display for user
}


function addRegiteredUser($password, $bankaccount, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber) { //inscrire un nouvel utilisateur (avec ou sans droit de recharge des trottinettes)
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


function infoComplnScooter($sid) { // consulter les informations associées à chaque trottinette: état de la batterie, plaintes actuelles.

    $info_req = "SELECT `scooterID`, `modelNumber`, `commissioningDate`, `batteryLevel`
                 FROM `SCOOTERS`
                 WHERE `scooterID` = $sid";
    $result = mysqli_query($GLOBALS['link'], $info_req);
    if (mysqli_num_rows($result) > 0) {
            $data1 = mysqli_fetch_assoc($result);
    }
    $complain_req = "SELECT `userID`, `date`, `description`
                     FROM `COMPLAINS`
                     WHERE `scooterID` = $sid
                     ORDER BY `date`";
    $items = array();
    $count = 0;
    $result2 = mysqli_query($GLOBALS['link'], $complain_req);
    if ($result2->num_rows > 0) {
        while($row = $result2->fetch_assoc()) {
            $items[$count] = ($row);
            $count++;
            //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
        }
    }
    else {
        echo "Didn't find any results.\n";
    }
    $res = array();
    $res[0] = $data1;
    $res[1] = $items;
    return $res;
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


/*function organizeRecharge($sid, $uid, ) {
  $allow_rchrg_req = "SELECT `ID`
                      FROM `ALL_USERS`
                      WHERE `lastname` IS NOT NULL AND `ID` = $uid ";
  $result = mysqli_query($GLOBALS['link'], $allow_rchrg_req);
  if (mysqli_num_rows($result) > 0) {
    $adding_rchrg = "INSERT INTO `ALL_USERS` (ID, password, bankaccount, lastname, firstname, phone)
                     VALUES ($ID, '$password', '$bankaccount', '$lastname', '$firstname', '$phone')";
    if (!(mysqli_query($GLOBALS['link'], $adding))) {
        echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']),"\n";
    }
  }

}*/








mysqli_close($link);
?>
