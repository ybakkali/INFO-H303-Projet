<?php

	function isLoggedIn() {
		if (!isset($_SESSION['ID']) || empty($_SESSION['ID'])) {
			return false;
		}
		return true;
	}
?>