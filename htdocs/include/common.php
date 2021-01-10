<?php

function loggedin() {
	return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
}

session_start();

?>
