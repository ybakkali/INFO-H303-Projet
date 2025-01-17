<?php
session_start();
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
        echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
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
        echo mysqli_error($GLOBALS['link']),"\n";
        return false;
    }
    $adding_adrs = "INSERT INTO `USER_ADDRESS` (ID, city, cp, street, number)
                    VALUES ($ID, '$adrsCity', $adrsZip, '$adrsStreet', $adrsNumber)";
    if (!(mysqli_query($GLOBALS['link'], $adding_adrs))) {
        echo mysqli_error($GLOBALS['link']),"\n";
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
    $info_req = "SELECT `scooterID`, `modelNumber`, `commissioningDate`, `batteryLevel`, `locationX`, `locationY`, `availability`
                 FROM `SCOOTERS`
                 WHERE `scooterID` = $sid";
    $result = mysqli_query($GLOBALS['link'], $info_req);
    if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
    }
    else echo "Didn't find any results.\n";
}

function getAllScootersInfo($sortBy) { // consulter les informations associées à toutes les trottinettes.
    $info_req = "SELECT `scooterID`,`commissioningDate`,`modelNumber`,`totalComplaints`,`batteryLevel`,`locationX`,`locationY`,`lastLocationTime`,`availability`
                 FROM `SCOOTERS`
                 LEFT JOIN (SELECT `scooterID`,count(*) AS `totalComplaints`
                            FROM `COMPLAINTS`
                            WHERE `state` = 'notTreated' OR `state` = 'inProcess'
                            GROUP BY `scooterID`) computeComplains
                            USING (scooterID)
                 ORDER BY $sortBy DESC";
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

function getScooterComplains($sid,$sortBy){ // consulter les informations associées à chaque trottinette: état de la batterie, plaintes actuelles.
    $complain_req = "SELECT `userID`, `date`, `state`,`description`
                     FROM `COMPLAINTS`
                     WHERE `scooterID` = $sid
                     ORDER BY $sortBy DESC";
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
        //echo "Didn't find any results.\n";
    }
    return $items;
}


function complainScooter($scooter_id, $user_id, $cmpln_text) { //introduire une plainte (demande d'intervention) au sujet d'une trottinette
  $adding = "INSERT INTO `COMPLAINTS` (scooterID, userID, description)
             VALUES ($scooter_id, $user_id, '$cmpln_text')";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
}

function reparationScooter($scooter_id, $userID, $mechanic_id, $date, $note_text) {
  $adding = "INSERT INTO `REPARATIONS` (scooterID, userID, mechanicID, complainTime, note)
             VALUES ($scooter_id, $userID, $mechanic_id, '$date', '$note_text')";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
  else
    echo "<script>
            alert('Complaint successfully updated');
          </script>";
}

function userTripsHistory($uid,$sortBy) { //consulter l'historique des déplacement effectués
    $history_req = "SELECT *
                    FROM `TRIPS`
                    WHERE `userID` = $uid
                    ORDER BY $sortBy DESC";

    $result = $GLOBALS['link']->query($history_req);

    $items = array();
    $count = 0;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[$count] = ($row);
            $count++;
            //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
        }
        //print_r($items);
    }
    else {
        echo "Didn't find any results.\n";
    }
    return $items;
}

function addScooter($model, $x, $y) { // insérer une (nouvelle) trottinette dans le système
  $adding = "INSERT INTO `SCOOTERS` (modelNumber, locationX, locationY, lastLocationTime)
             VALUES ('$model', $x, $y,CURRENT_TIMESTAMP)";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
}

function reloadScooter($sid,$uid) {
  $adding = "INSERT INTO `RELOADS`(scooterID,userID)
             VALUES($sid,$uid)";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
}

function bringBackScooter($sid,$uid,$x,$y) {
  $adding = "UPDATE`RELOADS`
             SET finalLoad = 4, destinationX = $x, destinationY = $y, endtime = CURRENT_TIMESTAMP
             WHERE scooterID = $sid AND userID = $uid";
  if (!(mysqli_query($GLOBALS['link'], $adding))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
}

function removeScooter($sid) { //supprimer une (nouvelle) trottinette dans le système
  updateScooterStatus($sid, 'defective');
}

function repairScooter($sid) {
  updateScooterStatus($sid, 'inRepair');
}

function fixScooter($sid) {
  updateScooterStatus($sid, 'available');
}

function reserveScooter($sid,$uid) {
    $extra = "INSERT INTO `EXTRA_PAYMENT` (scooterID,userID,price,type)
              VALUES ($sid, $uid, 3, 'reservation')";
    if (!(mysqli_query($GLOBALS['link'], $extra))) {
        echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
    }
}

function updateScooterStatus($sid, $new_status) { //actualiser le statut de chaque trottinette (libre, utilisée, en recharge, . . . )
  $update_status = "UPDATE `SCOOTERS`
                    SET `availability` = '$new_status'
                    WHERE `scooterID` = $sid";
  if (!(mysqli_query($GLOBALS['link'], $update_status))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."') </script>";
  }
}

function updateComplaintState($uid, $sid, $date, $new_state) { //gérer, traiter et clôturer les plaintes (demandes d'intervention), écrire une éventuelle note technique.
  $update_state = " UPDATE `COMPLAINTS`
                    SET `state` = '$new_state'
                    WHERE `scooterID` = $sid AND `userID` = $uid AND `date` = '$date'";
  if (!(mysqli_query($GLOBALS['link'], $update_state))) {
      echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
  }
  else
    echo "<script>
            alert('Complaint successfully updated');
          </script>";

}

function convertToRegUser($uid, $lastname, $firstname, $phone, $adrsCity, $adrsZip, $adrsStreet, $adrsNumber){ //faire évoluer un utilisateur lambda en utilisateur avec droit de recharge des trottinettes.

          $new_id = getRegisteredID();
          $update_user = "UPDATE `ALL_USERS`
                          SET `ID` = $new_id, `lastname` = '$lastname', `firstname` = '$firstname', `phone` = '$phone'
                          WHERE `ID` = $uid";
          if (!(mysqli_query($GLOBALS['link'], $update_user))) {
              echo "<script> alert('".mysqli_error($GLOBALS['link'])."')</script>";
              return false;
          }
          $adding_adrs = "INSERT INTO `USER_ADDRESS` (ID, city, cp, street, number)
                          VALUES ($new_id, '$adrsCity', $adrsZip, '$adrsStreet', $adrsNumber)";
          if (!(mysqli_query($GLOBALS['link'], $adding_adrs))) {
              echo mysqli_error($GLOBALS['link']),"\n";
              return false;
          }
          return true;
}


function getAllUsersInfo() {
    $req1 = "SELECT u.`ID`, u.`lastname`, u.`firstname`, u.`phone`, u.`bankaccount`, a.`city`, a.`cp`, a.`street`, a.`number`
            FROM `ALL_USERS` u, `USER_ADDRESS` a
            WHERE u.`ID` = a.`ID`";

    $result = $GLOBALS['link']->query($req1);

    $items = array();
    $count = 0;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $items[$count] = ($row);
            $count++;
            //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
        }
        //print_r($items);
    }
    else {
        echo "Didn't find any results.\n";
    }

    $req2 = "SELECT u.`ID`, u.`bankaccount`
             FROM `ALL_USERS` u
             WHERE u.`lastname` IS NULL";

    $result2 = $GLOBALS['link']->query($req2);

    if ($result2->num_rows > 0) {
        while($row2 = $result2->fetch_assoc()) {
            $items[$count] = ($row2);
            $count++;
            //echo "Scooter ID: " . $row["scooterID"]. " - Position: " . $row["locationX"]. "," . $row["locationY"]. "\n";
        }

    }
    else {
        echo "Didn't find any results.\n";
    }


    return $items;
}


function getR1() {
  $r1 = "  SELECT s.`scooterID`, s.`locationX`, s.`locationY`
           FROM `SCOOTERS` s
           WHERE s.`availability` = 'available'";
  $result = $GLOBALS['link']->query($r1);

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
      //echo "Didn't find any results.\n";
  }
  //print_r($items);
  return $items;
}


function getR2() {
  $r2  = "SELECT DISTINCT
          userID
      FROM
          RELOADS
      WHERE
          userID NOT IN(
          SELECT DISTINCT
              userID
          FROM
              (
              SELECT
                  userID,
                  scooterID
              FROM
                  RELOADS
              UNION ALL
          SELECT
              userID,
              scooterID
          FROM
              (
              SELECT DISTINCT
                  userID,
                  scooterID
              FROM
                  RELOADS
              INNER JOIN TRIPS USING(userID, scooterID)
          ) alias1
          ) alias2
      GROUP BY
          userID,
          scooterID
      HAVING
          COUNT(*) = 1
      )";


  $result = $GLOBALS['link']->query($r2);

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
      //echo "Didn't find any results.\n";
  }
  //print_r($items);
  return $items;
}


function getR3() {
  $r3 = "  SELECT T.`scooterID`
           FROM `TRIPS` T
           GROUP BY T.`scooterID`
           HAVING sum(ST_Distance_Sphere(point(T.`sourceX`, T.`sourceY`), point(T.`destinationX`, T.`destinationY`)))

       =( SELECT max(`Distance`)
          FROM ( SELECT S.`scooterID`, sum(ST_Distance_Sphere(point(S.`sourceX`, S.`sourceY`), point(S.`destinationX`, S.`destinationY`))) as `Distance`
                 FROM `TRIPS` S
                 GROUP BY S.`scooterID`
                ) SUM_SCOOTER_DISTANCE
        )";
  $result = $GLOBALS['link']->query($r3);


  if (mysqli_num_rows($result) > 0) {
          $data = mysqli_fetch_assoc($result);
  }
  //print_r($data["scooterID"]);
  return $data["scooterID"];
}


function getR4() {
  $r4 = "  SELECT `scooterID`
           FROM `REPARATIONS`
           GROUP BY `scooterID`
           HAVING count(`scooterID`) >= 10";
  $result = $GLOBALS['link']->query($r4);

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
      //echo "Didn't find any results.\n";
  }
  //print_r($items);
  return $items;
}



function getR5() {
  $r5 = "  SELECT `userID`,
        count(`userID`) as `Total trips`,
        SEC_TO_TIME(AVG(TIME_TO_SEC(`duration`))) as `Average duration`,
        sum(`price`) AS `Total amount (€)`
        FROM `TRIPS`
        GROUP BY `userID`
        HAVING count(`userID`) >= 10";

  $result = $GLOBALS['link']->query($r5);

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
      //echo "Didn't find any results.\n";
  }
  //print_r($items);
  return $items;
}

?>
