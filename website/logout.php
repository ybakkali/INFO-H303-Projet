<html>
<body>
	<title> DataBase Project - Log Out </title>
	<?php
		session_start();
		session_destroy();
	?>
	<meta http-equiv="refresh" content="0; URL='home'"/>
</body>
</html>
