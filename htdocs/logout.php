<?php

// Initialize the session
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to homepage
header("location: /");

?>
