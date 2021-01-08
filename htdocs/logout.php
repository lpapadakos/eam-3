<?php

// Initialize the session
require_once "common.php";

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to homepage
header("location: /");

?>
