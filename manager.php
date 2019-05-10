<?php
//do "sudo apt-get install php-mysql" before using

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

function userAuthentication($uid, $pass) {
  $user_login_req = "SELECT `lastname`
                    FROM `ALL_USERS`
                    WHERE `ID`=$uid AND `password`=$pass";
  $result = mysqli_query($GLOBALS['link'], $user_login_req);
  if (mysqli_num_rows($result) > 0) {
        $_SESSION["ID"] = $uid;
        if (mysqli_fetch_assoc($result)["lastname"])
            $_SESSION["type"] = "registered";
        else
            $_SESSION["type"] = "unregistered";
        return true;
  }
  else {
        return false;
  }
}


function mecAuthentication($mid, $pass) {
  $mec_login_req = "SELECT `mechanicID`, `password`
                    FROM  `MECANICIENS`
                    WHERE `mechanicID`=$mid AND `password`=$pass";
  $result = mysqli_query($GLOBALS['link'], $mec_login_req);
  if (mysqli_num_rows($result) > 0) {
        $_SESSION["ID"] = $mid;
        $_SESSION["type"] = "mechanic";
        return true;
  }
  else {
        return false;
  }
}


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
        return false;
    }
    else {
        $_SESSION["ID"] = $ID;
        $_SESSION["type"] = "unregistered";
        return true;
    }
}


function addRegiteredUser($password, $bankaccount, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber) { //inscrire un nouvel utilisateur (avec ou sans droit de recharge des trottinettes)
    $ID = getRegisteredID();
    //echo "Your ID is : ", $ID, "\n";
    $adding = "INSERT INTO `ALL_USERS` (ID, password, bankaccount, lastname, firstname, phone)
               VALUES ($ID, '$password', '$bankaccount', '$lastname', '$firstname', '$phone')";
    if (!(mysqli_query($GLOBALS['link'], $adding))) {
        echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']),"\n";
        return false;
    }
    $adding_adrs = "INSERT INTO `USER_ADDRESS` (ID, city, cp, street, number)
                    VALUES ($ID, '$adrsCity', $adrsZip, '$adrsStreet', $adrsNumber)";
    if (!(mysqli_query($GLOBALS['link'], $adding_adrs))) {
        echo "Error : (could not insert new data !) : " . $adding_adrs . "<br>" . mysqli_error($GLOBALS['link']),"\n";
        return false;
    }
    else {
        $_SESSION["ID"] = $ID;
        $_SESSION["type"] = "registered";
        return true;
    }
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


function getScooterInfo($sid) { // consulter les informations associées à chaque trottinette: état de la batterie, plaintes actuelles.
    $info_req = "SELECT `scooterID`, `modelNumber`, `commissioningDate`, `batteryLevel`, `locationX`, `locationY`
                 FROM `SCOOTERS`
                 WHERE `scooterID` = $sid";
    $result = mysqli_query($GLOBALS['link'], $info_req);
    if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
    }
    return $data;
}

function getAllScootersInfo() { // consulter les informations associées à toutes les trottinettes.
    $info_req = "SELECT * FROM `SCOOTERS`";
    $result = mysqli_query($GLOBALS['link'], $info_req);
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

function getScooterComplains($sid){ // consulter les informations associées à chaque trottinette: état de la batterie, plaintes actuelles.
    $complain_req = "SELECT `userID`, `date`, `description`
                     FROM `COMPLAINS`
                     WHERE `scooterID` = $sid
                     ORDER BY `date`";
    $items = array();
    $count = 0;
    $result = mysqli_query($GLOBALS['link'], $complain_req);
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


function complainScooter($scooter_id, $user_id, $cmpln_text) { //introduire une plainte (demande d'intervention) au sujet d'une trottinette
  $adding = "INSERT INTO `COMPLAINS` (scooterID, userID, description)
             VALUES ($scooter_id, $user_id, '$cmpln_text')";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
  }
}


function userTripsHistory($uid) { //consulter l'historique des déplacement effectués
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


/*function organizeRecharge($sid, $uid) {
    $allow_rchrg_req = "SELECT `ID`
                        FROM `ALL_USERS`
                        WHERE `lastname` IS NOT NULL AND `ID` = $uid ";
    $result = mysqli_query($GLOBALS['link'], $allow_rchrg_req);
    if (mysqli_num_rows($result) > 0) {
        $sctr_init_req = "SELECT `batteryLevel`, `locationX`, `locationY`
                          FROM `SCOOTER`
                          WHERE `scooterID` = $sid";
        $result = mysqli_query($GLOBALS['link'], $sctr_init_req);
        if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
        }
        $init_chrg = $data['batteryLevel'];
        $init_x = $data['locationX'];
        $init_y = $data['locationY'];
        $adding_rchrg = "INSERT INTO `RELOADS` (scooterID, userID, initialLoad, finalLoad, sourceX, sourceY, destinationX, destinationY, starttime, endtime)
                         VALUES ($sid, $uid, $init_chrg, finalLoad, $init_x, $init_y, destinationX, destinationY, starttime, endtime)";
        if (!(mysqli_query($GLOBALS['link'], $adding))) {
            echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']),"\n";
        }
    }
    else {
      return false;
    }
}*/



function getNewScooterID() {
    $last_sid_req = "SELECT max(`scooterID`)
                     FROM `SCOOTERS`";
    $result = mysqli_query($GLOBALS['link'], $last_sid_req);
    if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
    }
    return $data["max(`scooterID`)"] + 1;
}


function addScooter($model) { // insérer/supprimer une (nouvelle) trottinette dans le système
  $sid = getNewScooterID();
  $adding = "INSERT INTO `SCOOTERS` (scooterID, modelNumber)
             VALUES ($sid, '$model')";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
  }
}


function crossOutScooter($sid) { //(remove (?)) - insérer/supprimer une (nouvelle) trottinette dans le système
  /*$adding = "DELETE FROM `SCOOTERS`
             WHERE `scooterID` = $sid";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
  }*/
  updateScooterStatus($sid, 'inRepair');
}


function updateScooterStatus($sid, $new_status) { //actualiser le statut de chaque trottinette (libre, utilisée, en recharge, . . . )
  $update_status = "UPDATE `SCOOTERS`
                    SET `availability` = $new_status
                    WHERE `scooterID` = $sid";
  if (!(mysqli_query($GLOBALS['link'], $update_status))) {
      echo "Error : (could not insert new data !) : " . $adding . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
  }
}




function covertToRegUser($uid, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber){ //faire évoluer un utilisateur lambda en utilisateur avec droit de recharge des trottinettes.
          /*$usrinfo_req = "SELECT `password`, `bankaccount`
                          FROM `ALL_USERS`
                          WHERE `ID` = $uid";
          $result = mysqli_query($GLOBALS['link'], $usrinfo_req);
          if (mysqli_num_rows($result) > 0) {
                  $data = mysqli_fetch_assoc($result);
                  $deleting = "DELETE FROM `ALL_USERS`
                               WHERE `ID`=$uid";
                  if (!(mysqli_query($GLOBALS['link'], $deleting))) {
                      echo "Error : (could not delete data !) : " . $deleting . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
                  }
                  //print_r($data['password']);
                  $password = $data['password'];
                  $bankaccount = $data['bankaccount'];
                  addRegiteredUser($password, $bankaccount, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber);
          }
          else {
            echo "ERROR\n";
          }*/
          $new_id = getRegisteredID();
          $update_user = "UPDATE `ALL_USERS`
                          SET `ID` = $new_id, `lastname` = '$lastname', `firstname` = '$firstname', `phone` = '$phone'
                          WHERE `ID` = $uid";
          if (!(mysqli_query($GLOBALS['link'], $update_user))) {
              echo "Error : (could not insert new data !) : " . $update_user . "<br>" . mysqli_error($GLOBALS['link']). "<br>";
          }
          $adding_adrs = "INSERT INTO `USER_ADDRESS` (ID, city, cp, street, number)
                          VALUES ($new_id, '$adrsCity', $adrsZip, '$adrsStreet', $adrsNumber)";
          if (!(mysqli_query($GLOBALS['link'], $adding_adrs))) {
              echo "Error : (could not insert new data !) : " . $adding_adrs . "<br>" . mysqli_error($GLOBALS['link']),"\n";
          }
}





//mysqli_close($link);
?>
