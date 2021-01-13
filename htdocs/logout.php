<?php

// Initialize the session
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to original page, unless it's only accessible while logged in (e.g. profile page)
if (isset($_GET['page']) && substr($_GET['page'], 0, 8) != "/profile") {
	$ret = $_GET['page'];
} else {
	$ret = "/";
}

// Redirect to referring page
header("location: " . $ret);

?>
