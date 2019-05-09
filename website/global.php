<?php

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function isLoggedIn() {
		if (!isset($_SESSION['ID']) || empty($_SESSION['ID'])) {
			return false;
		}
		return true;
	}
?>
