<?php

// Initialize the session
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

if (isset($_GET['url'])) {
	$ret = $_GET['url'];
} else {
	$ret = "/";
}

// Redirect to referring page
header("location: " . $ret);

?>
