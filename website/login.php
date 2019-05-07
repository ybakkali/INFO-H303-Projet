<html>
<body>
	<head><link rel="stylesheet" type="text/css" href="style.css"></head>
	<title> DataBase Project - Login </title>
	<?php include("global.php");?>
	<?php session_start();?>
	<?php include("header.php");?>

<h1 style="padding:100px">Login</h1>

<?php

$ID = $password ="";
$IDErr = $passwordErr = "";

$ready = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// ID
	$ready = true;
	if (empty($_POST["ID"])) {
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
	if ($ready) {
		verifyLogin($ID,$password);
		echo "<meta http-equiv=\"refresh\" content=\"0; URL='menu'\"/>";
	}
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function verifyLogin($ID,$password) {
	if (true) {
		$_SESSION["ID"] = $ID;
	}
}
/*
function verificarCliente($login, $password) {

        $sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
        if(($rs=$this->bd->executarSQL($sql))){
            if(mysql_fetch_row($rs)==false) {
                return false;
            } else {
                session_start();
                $the_username = // grab the username from your results set  
                $_SESSION['username'] = $the_username; 

                // put other things in the session if you like
                echo "<br><b> <a>Bem-Vindo <font size=2>" .mysql_result($rs,0,"login")."</font></b></a><br><br><br>";     

                return true;

            }
        }
        else {
            return false;
        }
    }
*/

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<h3 style = "position:relative; top:-75px">ID<br><input type="text" name="ID">
	<br><span class="error"><font color="red"><?php echo $IDErr;?></font></span>
	<br><br>
	Password<br><input type="password" name="password">
	<br><span class="error"><font color="red"><?php echo $passwordErr;?></font></span>
	<br><br>
	<input type="submit" name="submit" value="Submit"> </h3>
</form>

<?php include("footer.php");?>
</body>
</html>
