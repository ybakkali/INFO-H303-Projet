<html><body>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
	body,h1,h2,h3,h4,h5,h6 {font-family: "Raleway", sans-serif}

	body, html {
	  height: 100%;
	  line-height: 1.8;
	}

	/* Full height image header */
	.bgimg-1 {
	  background-position: center;
	  background-size: cover;
	  background-image: url("background-blur-clean-531880.jpg");
	  min-height: 100%;
	}

	.w3-bar .w3-button {
	  padding: 16px;
	}
</style>

	<div class="w3-top">
		<div class="w3-bar w3-white w3-card" id="myNavbar">
			<a href="home" class="w3-bar-item w3-button w3-wide">QLF Scooters</a>
			<!-- Right-sided navbar links -->
			<div class="w3-right w3-hide-small">
				<?php
					if (isloggedIn()) {
						echo '<a href="menu" class="w3-bar-item w3-button"><i class="fa fa-user"></i> Menu</a>';
						echo '<a href="logout" class="w3-bar-item w3-button"><i class="fa fa-pencil"></i> Log Out</a>';
					}
					else {
						echo '<a href="login" class="w3-bar-item w3-button"><i class="fa fa-user"></i> Login</a>';
						echo '<a href="register" class="w3-bar-item w3-button"><i class="fa fa-pencil"></i> Register</a>';
					}
				?>
				<!--<a href="login" class="w3-bar-item w3-button"><i class="fa fa-user"></i> Login</a>
				<a href="register" class="w3-bar-item w3-button"><i class="fa fa-pencil"></i> Register</a>-->
			</div>
		</div>
	</div>
</body></html>