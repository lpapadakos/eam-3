<?php

function referrer() {
	return "page=" . str_replace('index.php', '', $_SERVER["PHP_SELF"]);
}

function loggedin() {
	return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
}

function perk($input, $hide = true) {
	if (loggedin()) {
		echo 'value="' . $input . '"';
		if ($hide == true) echo 'disabled';
	}
}

session_start();

?>
