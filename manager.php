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
echo "Connected successfully \n";


$req = "SELECT scooterID FROM SCOOTERS";
$result = mysqli_query($link, $req);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "scooterID: " . $row["scooterID"].  "<br>";
    }
}
else {
    echo "0 results";
}

mysqli_close($link);


?>
