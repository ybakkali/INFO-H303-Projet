<html>
<title>DataBase Project</title>
<body>
	<h1>Trottinettes</h1>
	Best website ever!

<h1>Login</h1>

<?php

$username = $password ="";
$usernameErr = $passwordErr = "";

$ready = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// Username
	$ready = true;
	if (empty($_POST["username"])) {
		$usernameErr = "Username is required";
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
		verifyLogin($username,$password);
	}
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function verifyLogin($username,$password) {
	if (true) {
		session_start();
		$_SESSION["username"] = $username;
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
	Username: <input type="text" name="username">
	<span class="error"><font color="red"><?php echo $usernameErr;?></font></span>
	<br><br>
	Password: <input type="password" name="password">
	<span class="error"><font color="red"><?php echo $passwordErr;?></font></span>
	<br><br>
	<input type="submit" name="submit" value="Submit">
</form>

<a href="about">Back</a>

</body>
</html>
