<?php

function loggedin() {
	// Initialize the session aray
	session_start();

	return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
}

?>
